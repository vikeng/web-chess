<!DOCTYPE html>
<html>
	<head>
		<title>WebChess</title>
		<meta charset="UTF8">
		<link type="text/css" rel="stylesheet" href="chessboardjs/css/chessboard-0.3.0.css">
		<link type="text/css" rel="stylesheet" href="css/main.css">
		<link type="text/css" rel="stylesheet" href="themes/default/default.css">
		<link type="text/css" rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
		<script src="jquery/jquery-2.1.3.min.js"></script>
		<script src="jquery-ui/jquery-ui.min.js"></script>
		<script src="colResizable-1.5/colResizable-1.5.min.js"></script>
		<script src="chessboardjs/js/chessboard-0.3.0.js"></script>
		<script src="chessjs/chess.js"></script>
	</head>
	<body>
		<div id="mainNav">
			<ul>
				<li id="mainNav-openDatabaseButton">Open Database</li>
				<li>Create new database</li>
				<li>New game</li>
				<li>Save game</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div id="modeNav">
			<ul>
				<li>Mode 1</li>
				<li>Mode 2</li>
				<li>Mode 3</li>
			</ul>
		</div>
		
		<div id="main">
			<div id="main-chessboardWrapper">
				<ul id="main-chessboardWrapper-buttons">
					<li><input type="button" id="flipOrientationButton" class="button" value="Flip orientation"></li>
					<li><input type="button" id="undoButton" class="button" value="Undo last move"></li>
				</ul>
				<div class="clear"></div>
				<div id="main-chessboardWrapper-chessboard" style="width: 400px;"></div>
			</div>
			
			<div id="main-windows">
				<ul>
					<li><a href="#main-windows-1">PGN</a></li>
					<li><a href="#main-windows-games">Games</a></li>
					<li><a href="#main-windows-3">Analysis Engine</a></li>
				</ul>
				<div id="main-windows-1">
					<p>FEN: <span id="fen"></span></p>
					<p>PGN: <span id="pgn"></span></p>
				</div>
				<div id="main-windows-games">
					<table id="main-windows-games-gameList" width="100%">
							<th>White</th>
							<th>Black</th>
							<th>Result</th>
							<th>ECO</th>
							<th>Site</th>
							<th>Event</th>
							<th>Round</th>
							<th>Date</th>
							<th>Tags</th>
						</tr>
					</table>
				</div>
				<div id="main-windows-3">

				</div>
			</div>
			
			<div class="dialog" id="openDatabaseDialog" style="width: 500px; position: absolute; top: 100px; left: 300px;">
				<div class="closeDialog"></div>
				<p>List of Databases:</p>
				<ul id="openDatabaseDialog-databaseList">
					
				</ul>
			</div>
		</div>
		
		<script>
			var board,
			  game = new Chess(),
			  fenEl = $('#fen'),
			  pgnEl = $('#pgn');

			// do not pick up pieces if the game is over
			// only pick up pieces for the side to move
			var onDragStart = function(source, piece, position, orientation) {
			  if (game.game_over() === true ||
				  (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
				  (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
				return false;
			  }
			};

			var onDrop = function(source, target) {
			  // see if the move is legal
			  var move = game.move({
				from: source,
				to: target,
				promotion: 'q' // NOTE: always promote to a queen for example simplicity
			  });

			  // illegal move
			  if (move === null) return 'snapback';

			  updateStatus();
			};

			// update the board position after the piece snap 
			// for castling, en passant, pawn promotion
			var onSnapEnd = function() {
			  board.position(game.fen());
			};

			var updateStatus = function() {
			  fenEl.html(game.fen());
			  pgnEl.html(game.pgn());
			};

			var cfg = {
			  draggable: true,
			  position: 'start',
			  onDragStart: onDragStart,
			  onDrop: onDrop,
			  onSnapEnd: onSnapEnd
			};
			board = new ChessBoard('main-chessboardWrapper-chessboard', cfg);

			updateStatus();
		</script>
		<script src="js/ui.js"></script>
	</body>
</html>
