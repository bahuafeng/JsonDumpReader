<?php

namespace Tests\Wikibase\JsonDumpReader;

use Wikibase\JsonDumpReader\Bz2DumpReader;

/**
 * @covers Wikibase\JsonDumpReader\Bz2DumpReader
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Bz2DumpReaderTest extends \PHPUnit_Framework_TestCase {

	private function assertFindsEntity( Bz2DumpReader $reader, $expectedId ) {
		$line = $reader->nextJsonLine();
		$this->assertJson( $line );
		$this->assertContains( $expectedId, $line );
	}

	public function testGivenInvalidPath_exceptionIsThrown() {
		$this->setExpectedException( 'RuntimeException' );
		$reader = new Bz2DumpReader( __DIR__ . '/../data/does-not-exist.json.bz2' );
		$reader->nextJsonLine();
	}

	public function testGivenFileWithNoEntities_nullIsReturned() {
		$reader = new Bz2DumpReader( __DIR__ . '/../data/empty-dump.json.bz2' );

		$this->assertNull( $reader->nextJsonLine() );
	}

	public function testGivenFileWithFiveEntites_fiveEntityAreFound() {
		$reader = new Bz2DumpReader( __DIR__ . '/../data/five-entities.json.bz2' );

		$this->assertFindsEntity( $reader, 'Q1' );
		$this->assertFindsEntity( $reader, 'Q8' );
		$this->assertFindsEntity( $reader, 'P16' );
		$this->assertFindsEntity( $reader, 'P19' );
		$this->assertFindsEntity( $reader, 'P22' );
		$this->assertNull( $reader->nextJsonLine() );
	}

	public function testRewind() {
		$reader = new Bz2DumpReader( __DIR__ . '/../data/five-entities.json.bz2' );

		$this->assertFindsEntity( $reader, 'Q1' );
		$this->assertFindsEntity( $reader, 'Q8' );
		$reader->rewind();
		$this->assertFindsEntity( $reader, 'Q1' );
	}

}