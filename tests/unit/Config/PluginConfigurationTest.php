<?php

namespace SubstitutionPlugin\Config;

use SubstitutionPlugin\BaseUnitTestCase;

class PluginConfigurationTest extends BaseUnitTestCase
{
    /**
     * @dataProvider provideInvalidConfiguration
     * @param array $extra
     */
    public function testInvalidConfiguration(array $extra)
    {
        $config = new PluginConfiguration($extra);
        self::assertFalse($config->isEnabled());
    }

    public function provideInvalidConfiguration()
    {
        return array(
            array(array()),
            // wrong format
            array(array('substitution' => false)),
            // empty
            array(array('substitution' => array())),
            // mapping is missing
            array(array('substitution' => array('enable' => true))),
            // mapping has a wrong format
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => false,
            ))),
            // empty mapping
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(),
            ))),
            // substitution has a wrong format
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    'ph' => false,
                ),
            ))),
            // substitution has no type
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    'ph' => array('value' => 'foo'),
                ),
            ))),
            // substitution has no value
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    'ph' => array('type' => 'literal'),
                ),
            ))),
            // substitution has an invalid type
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    'ph' => array(
                        'value' => 'foo',
                        'type' => 'UNKNOWN TYPE',
                    ),
                ),
            ))),
            // substitution has an invalid value
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    'ph' => array(
                        'value' => array(),
                        'type' => 'literal',
                    ),
                ),
            ))),
            // placeholder is empty
            array(array('substitution' => array(
                'enable' => true,
                'mapping' => array(
                    '' => array(
                        'value' => 'foo',
                        'type' => 'literal',
                    ),
                ),
            ))),
        );
    }

    public function testWithValidConfiguration()
    {
        $extra = array('substitution' => array(
            'enable' => true,
            'mapping' => array(
                'ph' => array(
                    'value' => 'foo',
                    'type' => 'literal',
                ),
            ),
        ));
        $config = new PluginConfiguration($extra);
        self::assertTrue($config->isEnabled());
        self::assertEquals(0, $config->getPriority());
        /** @var SubstitutionConfiguration $subConf */
        $subConf = current($config->getMapping());
        self::assertEquals('ph', $subConf->getPlaceholder());
        self::assertEquals('foo', $subConf->getValue());
        self::assertEquals('literal', $subConf->getType());
        self::assertFalse($subConf->isCached());
    }
}
