<?php
declare(strict_types = 1);
namespace tests\rest;

use PHPUnit\Framework\TestCase;
use rest\Hateoas;

final class HateoasTest extends TestCase
{
    
    function test_constructor() {
        new Hateoas();
        
        // Suppress "This test did not perform any assertions"
        $this->assertTrue(true);
    }
    
    function test_addLink() {
        $expected = [
            'links' => [
                [
                    'href' => '/foo'
                ]
            ]
        ];
        
        $h = new Hateoas();
        $h->addLink('/foo');
        $actual = $h->export();
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_addLink_with_rel() {
        $expected = [
            'links' => [
                [
                    'href' => '/foo',
                    'rel' => 'foo'
                ]
            ]
        ];
        
        $h = new Hateoas();
        $h->addLink('/foo', 'foo');
        $actual = $h->export();
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_addText() {
        $expected = [
            'foo' => 'bar'
        ];
        
        $h = new Hateoas();
        $h->addText('foo', 'bar');
        $actual = $h->export();
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_addNamedCollection() {
        $expected = [
            'alphabet' => ['a', 'b', 'c']
        ];
        
        $h = new Hateoas();
        $h->addNamedCollection('alphabet', ['a', 'b', 'c']);
        $actual = $h->export();
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_export() {
        $expected = [];
        
        $h = new Hateoas();
        $actual = $h->export();
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_exportWithCollection() {
        $expected = [
            'collection' => ['foo']
        ];
        
        $h = new Hateoas();
        $actual = $h->exportWithCollection(['foo']);
        
        $this->assertEquals($expected, $actual);
    }
    
    function test_exportWithItem() {
        $expectedKey = 'item';
        
        $h = new Hateoas();
        $array = $h->exportWithItem('foo');
        
        $this->assertArrayHasKey($expectedKey, $array);
    }
    
    static function test_exportMessage() {
        $expected = [
            'message' => 'foo'
        ];
        $actual = Hateoas::exportMessage('foo');
        self::assertEquals($expected, $actual);
    }
    
}