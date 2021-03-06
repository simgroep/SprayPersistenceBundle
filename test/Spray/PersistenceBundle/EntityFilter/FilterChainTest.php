<?php

namespace Spray\PersistenceBundle\EntityFilter;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * FilterChainTest
 */
class FilterChainTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->filter = $this->getMock('Spray\PersistenceBundle\EntityFilter\EntityFilterInterface');
        $this->queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->filter->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));
    }
    
    protected function createFilterChain()
    {
        return new FilterChain();
    }
    
    public function getDefaultName()
    {
        $this->assertEquals('filter_chain', $this->createFilterChain()->getName());
    }
    
    public function testDoesNotHaveFilter()
    {
        $chain = $this->createFilterChain();
        $this->assertFalse($chain->hasFilter($this->filter));
    }
    
    public function testFailDoesNotHaveFilterAsArgumentIsInvalid()
    {
        $this->setExpectedException('Spray\PersistenceBundle\EntityFilter\Exception\InvalidArgumentException');
        $this->createFilterChain()->hasFilter(new stdClass);
    }
    
    public function testDoesNotHaveFilterByName()
    {
        $chain = $this->createFilterChain();
        $this->assertFalse($chain->hasFilter('test'));
    }
    
    public function testAddFilterHasFilter()
    {
        $chain = $this->createFilterChain();
        $chain->addFilter($this->filter);
        $this->assertTrue($chain->hasFilter($this->filter));
    }
    
    public function testAddFilterHasFilterByName()
    {
        $chain = $this->createFilterChain();
        $chain->addFilter($this->filter);
        $this->assertTrue($chain->hasFilter('test'));
    }
    
    public function testRemoveFilterHasNoFilter()
    {
        $chain = $this->createFilterChain();
        $chain->addFilter($this->filter);
        $chain->removeFilter($this->filter);
        $this->assertFalse($chain->hasFilter($this->filter));
    }
    
    public function testFailCanNotRemoveFilterAsArgumentIsInvalid()
    {
        $this->setExpectedException('Spray\PersistenceBundle\EntityFilter\Exception\InvalidArgumentException');
        $this->createFilterChain()->removeFilter(new stdClass);
    }
    
    public function testFailCanNotRemoveFilterThatWasNeverAdded()
    {
        $this->setExpectedException('Spray\PersistenceBundle\EntityFilter\Exception\UnexpectedValueException');
        $this->createFilterChain()->removeFilter('foo');
    }
    
    public function testRemoveFilterHasNoFilterByName()
    {
        $chain = $this->createFilterChain();
        $chain->addFilter($this->filter);
        $chain->removeFilter($this->filter);
        $this->assertFalse($chain->hasFilter('test'));
    }
    
    public function testFilterUsingOptions()
    {
        $this->filter->expects($this->once())
            ->method('filter')
            ->with(
                $this->identicalTo($this->queryBuilder),
                $this->equalTo(array('foo' => 'bar')));
        
        $chain = $this->createFilterChain();
        $chain->addFilter($this->filter, array('foo' => 'bar'));
        $chain->filter($this->queryBuilder);
    }
}
