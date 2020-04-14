<?php

namespace Zing\LaravelSms\Tests\Support;

use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function provideConfig()
    {
        return [
            [new Config([
                'int' => 1,
                'string' => 'harry',
                'user' => [
                    'name' => 'robin',
                ],
                'users' => [
                    [
                        'name' => 'harry',
                    ],
                    [
                        'name' => 'robin',
                    ],
                    [
                        'name' => 'hermine',
                    ],
                ],
            ])],
        ];
    }

    /**
     * @dataProvider provideConfig
     *
     * @param Config $config
     */
    public function test_get($config)
    {
        $this->assertSame(1, $config->get('int'));
        $this->assertSame('harry', $config->get('string'));
        $this->assertNull($config->get('nothing'));
        $this->assertSame('default', $config->get('nothing', 'default'));
        $this->assertSame('robin', $config->get('user.name'));
        $this->assertSame('harry', $config->get('users.0.name'));
    }

    /**
     * @dataProvider provideConfig
     *
     * @param Config $config
     */
    public function test_offset_set($config)
    {
        $this->assertSame('harry', $config->get('string'));
        $this->assertSame(1, $config['int']);
        $this->assertSame('harry', $config['string']);
        $this->assertNull($config['nothing']);
        $this->assertSame('robin', $config['user.name']);
        $this->assertSame('harry', $config['users.0.name']);
        $config['string'] = 'hermine';
        $this->assertSame('hermine', $config->get('string'));
        unset($config['string']);
        $this->assertNull($config->get('string'));
        $this->assertFalse(isset($config['string']));
    }
}
