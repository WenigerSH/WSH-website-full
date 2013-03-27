<?php

namespace Wsh\WebsiteBundle\Helper;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;

class SettingsManager {
    /**
     * @var string The path to the settings file
     */
    private $settingsFile;

    /**
     * @var mixed An array to keep all the loaded settings in.
     */
    private $settingsArray;

    /**
     * @var boolean The setters will only work if you set readOnly to false explicitly via the setter.
     */
    private $readOnly;

    public function __construct($pathToSerializedSettings)
    {
        if (!is_file($pathToSerializedSettings)) {
            throw new \Exception('The settings file could not be found');
        }
        $parser = new Parser();
        $this->settingsArray = $parser->parse(file_get_contents($pathToSerializedSettings));
        $this->readOnly = true;
        $this->settingsFile = $pathToSerializedSettings;
    }

    /**
     * The function to retrieve a setting. Uses dot notation to address settings. For example:
     * array('test' => array('someSetting' => VALUE))
     * To retrieve VALUE you would call get('test.someSetting')
     *
     * @param string $name Name of the setting using yaml dot notation
     * @return mixed|null The setting or null if not found
     */
    public function get($name)
    {
        $address = explode('.', $name);
        $current = $this->settingsArray;
        foreach($address as $part) {
            if (isset($current[$part])) {
                $current = $current[$part];
            } else {
                $current = null;
                break;
            }
        }
        return $current;
    }

    /**
     * @param boolean $ro
     */
    public function setReadOnly($ro)
    {
        $this->readOnly = $ro;
    }

    /**
     * Set a settings value. Name must be given in yaml dot notation.
     * If a setting does not exist in the file it will not be created.
     * This behaviour can be overridden by passing an associative array as the value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        if ($this->readOnly) {
            throw new \Exception('Settings are read only. Use setReadOnly(false) before altering settings.');
        }
        $address = explode('.', $name);
        $current = & $this->settingsArray;
        foreach($address as $part) {
            if (isset($current[$part])) {
                $current = & $current[$part];
            } else {
                $current = null;
                break;
            }
        }

        if ($current !== null) {
            $current = $value;
        }
    }

    /**
     * This function saves the settings in memory to the settings file.
     * Do all the validation before calling this function.
     */
    public function saveToFile()
    {
        if ($this->readOnly) {
            throw new \Exception('Settings are read only. Use setReadOnly(false) before altering settings.');
        }
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->settingsArray,3);
        file_put_contents($this->settingsFile, $yaml);
    }

    /**
     * @param string $name The name of the setting to fetch. If null reloads all settings from the file.
     */
    public function reloadFromFile($name = null)
    {
        $parser = new Parser();

        $originalSettings = $parser->parse(file_get_contents($this->settingsFile));
        if ($name !== null) {
            $address = explode('.', $name);
            $current = $originalSettings;
            foreach($address as $part) {
                if (isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    $current = null;
                    break;
                }
            }
            $ro = $this->readOnly;
            $this->readOnly = false;
            $this->set($name, $current);
            $this->readOnly = $ro;
            unset($ro);
        } else {
            $this->settingsArray = $originalSettings;
        }
    }

    /**
     * function to get the value lists for model in the given region
     *
     * @param string $name The name of the characteristic in plural form. ie. "heights"
     * @param string $region The shortcode for the region. Can be us, uk or eu. Defaults to eu
     *
     * @return array
     */
    public function getModelCharacteristicValues($name, $region = 'eu')
    {
        if (!isset($this->settingsArray['modelConstraints'][$name])) {
            throw new \Exception('There is no such characteristic as '.$name);
        }

        if (!in_array($region, array('eu', 'us', 'uk'))) {
            throw new \Exception('Invalid region specified. Valid regions: us, uk, eu');
        }

        if ($region === 'eu') {
            return array_combine(
                array_keys($this->settingsArray['modelConstraints'][$name]),
                array_keys($this->settingsArray['modelConstraints'][$name])
            );
        } else {
            return array_combine(
                array_keys($this->settingsArray['modelConstraints'][$name]),
                array_map(
                    function ($data) use ($region) {
                        return $data[$region];
                    },
                    $this->settingsArray['modelConstraints'][$name]
                )
            );
        }
    }

    /**
     * function to get any translatable settings array
     *
     * @param string $address the setting address of the translatables
     * @param string $locale The locale code
     *
     * @return array The keys are in the default locale, the values are translated into $locale
     */
    public function getTranslatableValues($address, $locale = 'en_US')
    {
        $translatables = $this->get($address);

        if ($translatables === null) {
            throw new \Exception('Translatable settings not found for address: '.$address);
        }

        $defaultLocale = key($this->settingsArray['general']['availableLocales']);

        if (!in_array($locale, array_keys($this->settingsArray['general']['availableLocales']))) {
            $locale = $defaultLocale;
        }

        return array_combine(
            array_map(
                function ($data) {
                    return $data['en_US'];
                },
                $translatables
            ),
            array_map(
                function ($data) use ($locale, $defaultLocale) {
                    return isset($data[$locale]) ? $data[$locale] : $data[$defaultLocale];
                },
                $translatables
            )
        );
    }

    /**
     * function to get data that can be used in a collection form field
     *
     * @param string $name The name of the characteristic in plural form. ie. "heights"
     * @param boolean $translatable Distinguishes between measurement region collection and translatable collection
     *
     * @return array
     */
    public function getDataForCollection($name, $translatable = false)
    {
        $data = $this->get($name);
        $return = array();
        if (!is_array($data)) {
            throw new \Exception('The requested setting is not of array type in '.__FUNCTION__);
        }
        if (!empty($translatable)) {
            return $data;
        } else {
            foreach($data as $eu => $other) {
                $return[] = array('eu' => $eu, 'us' => $other['us'], 'uk' => $other['uk']);
            }
        }

        return $return;
    }
}