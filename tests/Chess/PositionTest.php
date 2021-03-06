<?php namespace App\Chess;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValidPosition()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $this->assertInstanceOf('App\Chess\Position', $position);

        return $position;
    }

    public function testCanCreatePositionWithoutArguments()
    {
        $position = new Position();
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testLoadFEN()
    {
        $position = new Position();
        $position->loadFEN('4k3/8/8/8/3Q4/8/8/4K3 w - - 0 1');
        $this->assertEquals('4k3/8/8/8/3Q4/8/8/4K3 w - - 0 1', $position->getFEN());
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 2
     */
    public function testLoadFENEmptyArgument()
    {
        $position = new Position();
        $position->loadFEN(null);
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 4
     */
    public function testLoadFENBadArgumentType()
    {
        $position = new Position();
        $position->loadFEN(34);
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 110
     */
    public function testLoadInvalidFENSyntax()
    {
        $position = new Position();
        $position->loadFEN('4k3/8/8/8/3Q4/8/4K3 w - - 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 111
     */
    public function testLoadFENCastlingFlagsMissing()
    {
        $position = new Position();
        $position->loadFEN('4k3/8/8/8/3Q4/8/8/4K3 w  - 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 102
     */
    public function testMissingWhiteKing()
    {
        $position = new Position();
        $position->loadFEN('4k3/pppppppp/8/8/8/8/PPPPPPPP/8 w - - 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 102
     */
    public function testMissingBlackKing()
    {
        $position = new Position();
        $position->loadFEN('8/pppppppp/8/8/8/8/PPPPPPPP/4K3 w - - 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 103
     */
    public function testTooManyQueens()
    {
        $position = new Position();
        $position->loadFEN('8/QQQQQQQQ/QQ6/8/k7/7K/8/8 b - - 0 39');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 103
     */
    public function testTooManyPromotedPieces()
    {
        $position = new Position();
        $position->loadFEN('8/1k3P2/4P1Q1/3P1N1B/2Q2B2/BPNB2Q1/P5K1/2R5 w - - 0 42');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 106
     */
    public function testInvalidEnPassantNoPawn()
    {
        $position = new Position('3k4/pppppppp/8/8/8/8/PPPPPPPP/3K4 w - c6 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 106
     */
    public function testInvalidEnPassantWrongSideToMove()
    {
        $position = new Position('3k4/pp1ppppp/8/2p5/8/8/PPPPPPPP/3K4 b - c6 0 1');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 104
     */
    public function testSideNotToMoveInCheck()
    {
        $position = new Position('7R/8/8/7k/8/8/8/K7 w - - 0 39');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 105
     */
    public function testPawnsOnBackRank()
    {
        $position = new Position();
        $position->loadFEN('p7/8/8/8/k7/8/7K/8 w - - 0 39');
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 112
     */
    public function testWrongNumberOfSquaresOnRank()
    {
        $position = new Position();
        $position->loadFEN('k/8/8/K/8/8/8/8 w - - 0 1');
    }

    public function testPreservePositionTooFewKings()
    {
        $position = new Position();
        try {
            $position->loadFEN('8/pppppppp/8/8/8/8/PPPPPPPP/8 w - - 0 1');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testPreservePositionTooManyQueens()
    {
        $position = new Position();
        try {
            $position->loadFEN('8/QQQQQQQQ/QQ6/8/k7/7K/8/8 b - - 0 39');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testPreservePositionTooManyPromotedPieces()
    {
        $position = new Position();
        try {
            $position->loadFEN('8/1k3P2/4P1Q1/3P1N1B/2Q2B2/BPNB2Q1/P5K1/2R5 w - - 0 42');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testPreservePositionSideNotToMoveInCheck()
    {
        $position = new Position();
        try {
            $position->loadFEN('7R/8/8/7k/8/8/8/K7 w - - 0 39');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testPreservePositionPawnsOnBackRank()
    {
        $position = new Position();
        try {
            $position->loadFEN('p7/8/8/8/k7/8/7K/8 w - - 0 39');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    public function testPreservePositionInvalidFENSyntax()
    {
        $position = new Position();
        try {
            $position->loadFEN('k/8/8/K/8/8/8/8 w - - 0 1');
        } catch (\Exception $e) {
        }
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $position->getFEN());
    }

    /**
     * @depends testCreateValidPosition
     */
    public function testGetFEN($positionObject)
    {
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $positionObject->getFEN());
    }

    /**
     * @depends testCreateValidPosition
     */
    public function testGetArray($positionObject)
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $positionShouldBe = [
            ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
            ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
            ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
        ];

        $this->assertEquals($positionShouldBe, $position->getArray());
    }

    public function testReset()
    {
        $position = new Position('4k3/8/8/8/3Q4/8/8/4K3 w - - 0 1');
        $position->reset();
        $this->assertEquals(new Position(), $position);
    }

    public function testIsLegalQueen()
    {
        $position = new Position('4k3/8/8/8/3Q4/8/8/4K3 w - - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('d4', 'c3')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'a1')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'd8')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'h4')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c2')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'e8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd4')));
    }

    public function testIsLegalRook()
    {
        $position = new Position('4k3/8/8/8/3R4/8/8/4K3 w - - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c3')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'a1')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'd8')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'h4')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c2')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'e8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd4')));
    }

    public function testIsLegalBishop()
    {
        $position = new Position('4k3/8/8/8/3B4/8/8/4K3 w - - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('d4', 'c3')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'a1')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'h4')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c2')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'e8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd4')));
    }

    public function testIsLegalKnight()
    {
        $position = new Position('4k3/8/8/8/3N4/8/8/4K3 w - - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c3')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'a1')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'h4')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'c2')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'e2')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'f5')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'c6')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'e8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd4')));
    }

    public function testIsLegalKing()
    {
        $position = new Position('4k3/8/8/8/3K4/8/8/8 w - - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('d4', 'c3')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'a1')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'h4')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'c2')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'e8')));
        $this->assertFalse($position->isLegalMove(new Move('d4', 'd4')));
        $this->assertTrue($position->isLegalMove(new Move('d4', 'd3')));
    }

    public function testIsLegalPawn()
    {
        $position = new Position('4k3/8/8/8/b3r1n1/1P3P1p/3PP2P/4K3 w - - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('b3', 'a4')));
        $this->assertTrue($position->isLegalMove(new Move('b3', 'b4')));
        $this->assertTrue($position->isLegalMove(new Move('d2', 'd3')));
        $this->assertTrue($position->isLegalMove(new Move('d2', 'd4')));
        $this->assertTrue($position->isLegalMove(new Move('e2', 'e3')));
        $this->assertTrue($position->isLegalMove(new Move('f3', 'e4')));
        $this->assertTrue($position->isLegalMove(new Move('f3', 'g4')));

        $this->assertFalse($position->isLegalMove(new Move('b3', 'c4')));
        $this->assertFalse($position->isLegalMove(new Move('b3', 'b5')));
        $this->assertFalse($position->isLegalMove(new Move('b3', 'b2')));
        $this->assertFalse($position->isLegalMove(new Move('b3', 'a2')));
        $this->assertFalse($position->isLegalMove(new Move('e2', 'e4')));
        $this->assertFalse($position->isLegalMove(new Move('e2', 'f3')));
        $this->assertFalse($position->isLegalMove(new Move('h2', 'h3')));
        $this->assertFalse($position->isLegalMove(new Move('h2', 'h4')));
    }

    public function testIsLegalEnPassant()
    {
        $position = new Position('4k3/8/1P6/1PpP1pP1/8/8/8/4K3 w - c6 0 1');
        $this->assertTrue($position->isLegalMove(new Move('b5', 'c6')));
        $this->assertTrue($position->isLegalMove(new Move('d5', 'c6')));
        $this->assertFalse($position->isLegalMove(new Move('b6', 'c6')));
        $this->assertFalse($position->isLegalMove(new Move('g5', 'f6')));
    }

    public function testIsLegalCastling()
    {
        $position = new Position('4k3/8/8/8/8/8/8/R3K2R w KQ - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('e1', 'g1')));
        $this->assertTrue($position->isLegalMove(new Move('e1', 'c1')));

        $position = new Position('4k3/8/8/8/4r3/8/8/R3K2R w KQ - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'g1')));
        $this->assertFalse($position->isLegalMove(new Move('e1', 'c1')));

        $position = new Position('4k3/8/8/8/5r2/8/8/R3K2R w KQ - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'g1')));

        $position = new Position('4k3/8/8/8/3r4/8/8/R3K2R w KQ - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'c1')));

        $position = new Position('4k3/8/8/8/6r1/8/8/R3K2R w KQ - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'g1')));

        $position = new Position('4k3/8/8/8/2r5/8/8/R3K2R w KQ - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'c1')));

        $position = new Position('4k3/8/8/8/8/8/8/R3K2R w - - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'g1')));
        $this->assertFalse($position->isLegalMove(new Move('e1', 'c1')));


        $position = new Position('r3k2r/8/8/8/8/8/8/4K3 b kq - 0 1');
        $this->assertTrue($position->isLegalMove(new Move('e8', 'g8')));
        $this->assertTrue($position->isLegalMove(new Move('e8', 'c8')));

        $position = new Position('r3k2r/8/8/8/4R3/8/8/4K3 b kq - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e8', 'g8')));
        $this->assertFalse($position->isLegalMove(new Move('e8', 'c8')));

        $position = new Position('r3k2r/8/8/8/8/8/8/4K3 b - - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e8', 'g8')));
        $this->assertFalse($position->isLegalMove(new Move('e8', 'c8')));
    }

    public function testIsLegalLeavingKingInCheck()
    {
        $position = new Position('4k3/8/8/3rr3/8/8/4B3/4K3 w - - 0 1');
        $this->assertFalse($position->isLegalMove(new Move('e1', 'd1')));
        $this->assertFalse($position->isLegalMove(new Move('e2', 'd1')));
    }

    public function testInCheck()
    {
        $position = new Position('7R/8/8/7k/8/8/8/K7 b - - 0 39');
        $this->assertTrue($position->inCheck());
    }

    /**
     * @depends testCreateValidPosition
     */
    public function testDoMove($positionObject)
    {
        $positionObject->doMove(new Move('e2', 'e4'));
        $this->assertEquals('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', $positionObject->getFEN());
        $positionObject->doMove(new Move('d7', 'd5'));
        $this->assertEquals('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w KQkq d6 0 2', $positionObject->getFEN());
        $positionObject->doMove(new Move('e4', 'd5'));
        $this->assertEquals('rnbqkbnr/ppp1pppp/8/3P4/8/8/PPPP1PPP/RNBQKBNR b KQkq - 0 2', $positionObject->getFEN());
        $positionObject->doMove(new Move('c7', 'c5'));
        $this->assertEquals('rnbqkbnr/pp2pppp/8/2pP4/8/8/PPPP1PPP/RNBQKBNR w KQkq c6 0 3', $positionObject->getFEN());
        $positionObject->doMove(new Move('d5', 'c6'));
        $this->assertEquals('rnbqkbnr/pp2pppp/2P5/8/8/8/PPPP1PPP/RNBQKBNR b KQkq - 0 3', $positionObject->getFEN());
        $positionObject->doMove(new Move('b8', 'c6'));
        $this->assertEquals('r1bqkbnr/pp2pppp/2n5/8/8/8/PPPP1PPP/RNBQKBNR w KQkq - 0 4', $positionObject->getFEN());
        $positionObject->doMove(new Move('g1', 'f3'));
        $this->assertEquals('r1bqkbnr/pp2pppp/2n5/8/8/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 4', $positionObject->getFEN());
        $positionObject->doMove(new Move('e8', 'd7'));
        $this->assertEquals('r1bq1bnr/pp1kpppp/2n5/8/8/5N2/PPPP1PPP/RNBQKB1R w KQ - 2 5', $positionObject->getFEN());
        $positionObject->doMove(new Move('f1', 'c4'));
        $this->assertEquals('r1bq1bnr/pp1kpppp/2n5/8/2B5/5N2/PPPP1PPP/RNBQK2R b KQ - 3 5', $positionObject->getFEN());
        $positionObject->doMove(new Move('d8', 'a5'));
        $this->assertEquals('r1b2bnr/pp1kpppp/2n5/q7/2B5/5N2/PPPP1PPP/RNBQK2R w KQ - 4 6', $positionObject->getFEN());
        $positionObject->doMove(new Move('e1', 'g1'));
        $this->assertEquals('r1b2bnr/pp1kpppp/2n5/q7/2B5/5N2/PPPP1PPP/RNBQ1RK1 b - - 5 6', $positionObject->getFEN());

        $position = new Position('r3k2r/8/8/8/8/8/8/4K3 b kq - 0 1');
        $position->doMove(new Move('h8', 'h7'));
        $this->assertEquals('r3k3/7r/8/8/8/8/8/4K3 w q - 1 2', $position->getFEN());

        $position = new Position('r3k2r/8/8/8/8/8/8/4K3 b kq - 0 1');
        $position->doMove(new Move('e8', 'e7'));
        $this->assertEquals('r6r/4k3/8/8/8/8/8/4K3 w - - 1 2', $position->getFEN());

        $position = new Position('r3k2r/8/8/8/8/8/8/4K3 b kq - 0 1');
        $position->doMove(new Move('a8', 'a7'));
        $this->assertEquals('4k2r/r7/8/8/8/8/8/4K3 w k - 1 2', $position->getFEN());
    }

    public function testDoMovePromotion()
    {
        $position = new Position('8/5P1k/R7/8/8/8/BB6/6K1 w - - 1 30');
        $position->doMove(new Move('f7', 'f8', PROMOTION_KNIGHT));
        $this->assertEquals('5N2/7k/R7/8/8/8/BB6/6K1 b - - 0 30', $position->getFEN());
    }

    public function testParseValidSAN()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $this->assertEquals(new Move('a2', 'a4', PROMOTION_QUEEN, [3, 20]), $position->parseSAN('a4!! $20'));
        $position = new Position('r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq - 4 4');
        $this->assertEquals(new Move('e1', 'g1', PROMOTION_QUEEN, [2, 74]), $position->parseSAN('O-O? $74'));
    }

    public function testParseSANWithDiambiguation()
    {
        $position = new Position('rn3rk1/1bq1ppbp/p2p1np1/1p6/4PB2/2PB1N1P/PP2QPP1/R3RNK1 w - - 3 20');
        $this->assertEquals(new Move('f1', 'h2'), $position->parseSAN('N1h2'));
        $position = new Position('rn3rk1/1bq1ppbp/p2p1np1/1p6/4PB2/2PB1N1P/PP2QPP1/R3RNK1 b - - 3 20');
        $this->assertEquals(new Move('b8', 'd7'), $position->parseSAN('Nbd7'));
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 131
     */
    public function testParseSANWithoutEnoughDisambiguation()
    {
        $position = new Position('rn3rk1/1bq1ppbp/p2p1np1/1p6/4PB2/2PB1N1P/PP2QPP1/R3RNK1 w - - 3 20');
        $position->parseSAN('Nh2');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 132
     */
    public function testParseIllegelSAN()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $position->parseSAN('Nf4');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 130
     */
    public function testParseSANInvalidPieceName()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $position->parseSAN('Ge4');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 130
     */
    public function testParseSANInvalidDestination()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $position->parseSAN('Nj4');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 130
     */
    public function testParseSANInvalidDisambiguation()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $position->parseSAN('Njf3');
    }

    /**
     * @expectedException     App\Chess\PositionException
     * @expectedExceptionCode 130
     */
    public function testParseSANInvalidAnnotation()
    {
        $position = new Position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        $position->parseSAN('Nf3!!!!');
    }

    public function testPositionObjectsAreEqual()
    {
        $position1 = new Position('rnbqkb1r/pppppppp/5n2/8/2P5/8/PP1PPPPP/RNBQKBNR w KQkq - 1 2');
        $position2 = new Position();
        $position2->doMove(new Move('c2', 'c4'));
        $position2->doMove(new Move('g8', 'f6'));
        $this->assertEquals($position1, $position2);
    }
}
