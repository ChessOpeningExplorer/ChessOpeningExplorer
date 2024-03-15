<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chess Opening Details</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #797979;; /* Set background color to black */
            color: white; /* Set text color to white */
        }
        h1 {
            color: white; /* Set heading text color to white */
        }
        p {
            margin-bottom: 20px;
        }
        button {
            background-color: white; /* Set button background color to white */
            color: black; /* Set button text color to black */
            margin: 5px;
        }
        button:hover {
            background-color: #ddd; /* Set button background color on hover */
        }
    </style>

</head>
<body>


<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chessdb1");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve opening name from the URL parameter
$openingName = isset($_GET['opening']) ? urldecode($_GET['opening']) : null;


// Validate opening name (you may add more validation as needed)
if (!empty($openingName)) {
    // Query to retrieve additional information based on the opening name
    $query = "SELECT * FROM openings WHERE Nombre = '" . $openingName . "'";
    $result = $conn->query($query);
    $mvsequence = "SELECT MvtoUse FROM openings WHERE Nombre = '" . $openingName . "'";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h1>Details for Opening: " . $row["Nombre"] . "</h1>";
            echo "<p>Elo Rating: " . $row["Elo"] . "</p>";
            echo "<p>Open or Close: " . $row["cl_op"] . "</p>";
            echo "<p>Move Sequence: " . $row["MvSeq"] . "</p>";
            
            // Add more information as needed

            


            

            echo "<div id='board'></div>";

        }
    } else {
        echo "<p>No information found for the specified opening.</p>";
    }
} else {
    echo "<p>Invalid or missing opening name parameter.</p>";
}


$conn->close();
?>



<input type="submit" value="Backwards" id="backwardButton">
<input type="submit" value="Forward" id="forwardButton">


<script>
        // Function to parse chess notation
        function parseChessNotation(notation) {
            const pieceMapping = {
                'P': 'pawn',
                'N': 'knight',
                'B': 'bishop',
                'R': 'rook',
                'Q': 'queen',
                'K': 'king'
            };

              const piece = notation.charAt(0);

              const p_file = notation.charAt(1);
              const p_rank = notation.charAt(2);

              const file = notation.charAt(5);
              const rank = notation.charAt(6);
              const pieceName = pieceMapping[piece];
      
            return { piece: pieceName, file: file, rank: rank, PrevFile: p_file, PrevRank: p_rank};

        }

    </script>


<script>
  let moveIndex = 0; // Initialize move index
    function displayNextMove(moves) {
        if (moveIndex < moves.length) {
            const move = moves[moveIndex];
            const parsedMove = parseChessNotation(move);
            const squareId = parsedMove.file + parsedMove.rank;
            const piece = parsedMove.piece;
            const color = moveIndex % 2 === 0 ? 'white' : 'black'; // Alternating colors
            const square = document.querySelector(`.${squareId}`);
            square.innerHTML = `<img src="./images/pieces/${color}/${piece}.png" alt="" />`;
            
          const psquareID = parsedMove.PrevFile + parsedMove.PrevRank; 
          const psquare = document.querySelector(`.${psquareID}`);
          psquare.innerHTML = `<img src="" alt="" />`;

            moveIndex++; // Increment move index
        }
    }
  </script>


<script>
  function displayPreviousMove(moves) {
    const move = moves[moveIndex - 1]; // Retrieve the previous move
        const parsedMove = parseChessNotation(move);
        const prevSquareId = parsedMove.PrevFile + parsedMove.PrevRank;
        const prevPiece = parsedMove.piece;
        const color = (moveIndex-1) % 2 === 0 ? 'white' : 'black'; // Use current move index for color determination
        const prevSquare = document.querySelector(`.${prevSquareId}`);
        prevSquare.innerHTML = `<img src="./images/pieces/${color}/${prevPiece}.png" alt="" />`;

        const currentSquareId = parsedMove.file + parsedMove.rank;
        const currentSquare = document.querySelector(`.${currentSquareId}`);
        currentSquare.innerHTML = `<img src="" alt="" />`; // Empty the current square

        moveIndex--;    
}
</script> 



<script>
    // Function to parse URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    // Retrieve the opening name from the URL parameter
    var openingName = getUrlParameter('opening');


    var openings = {"Italian Game":["Pe2.Pe4", "Pe7.Pe5", "Ng1.Nf3", "Nb8.Nc6", "Bf1.Bc4"],"Scandinavian Defense":["Pe2.Pe4","Pd7.Pd5"],"French Defense":["Pe2.Pe4", "Pe7.Pe6"],"Queen's Gambit":["Pd2.Pd4", "Pd7.Pd5", "Pc2.Pc4"],"Caro-Kann Defense":["Pe2.Pe4","Pc7.Pc6"],"Sicilian Defense":["Pe2.Pe4", "Pc7.Pc5"],"Ruy Lopez":["Pe2.Pe4", "Pe7.Pe5","Ng1.Nf3", "Nb8.Nc6", "Bf1.Bb5"],"King's Indian Defense":["Pd2.Pd4", "Ng8.Nf6", "Pc2.Pc4", "Pg7.Pg6"],"Nimzo-Indian Defense":["Pd2.Pd4", "Ng8.Nf6", "Pc2.Pc4", "Pe7.Pe6", "Nb1.Nc3", "Bf8.Bb4"],"Alekhine Defense":["Pe2.Pe4","Ng8.Nf6"],"King's Gambit":["Pe2.Pe4", "Pe7.Pe5", "Pf2.Pf4"],"Benoni Defense":["Pd2.Pd4", "Pc7.Pc5"],"Dutch Defense":["Pd2.Pd4", "Pf7.Pf5"],"Slav Defense":["Pd2.Pd4", "Pd7.Pd5", "Pc2.Pc4", "Pc7.Pc6"],"Vienna Game":["Pe2.Pe4","Pe7.Pe5", "Nb1.Nc3"],"Catalan Opening":["Pd2.Pd4", "Ng8.Nf6", "Pc2.Pc4", "Pe7.Pe6","Pg2.Pg3"],"Queen's Indian Defense":["Pd2.Pd4", "Ng8.Nf6", "Pc2.Pc4", "Pe7.Pe6", "Ng1.Nf3", "Pb7.Pb6"],"King's Indian Attack":["Ng1.Nf3", "Pd7.Pd5", "Pg2.Pg3"],"Pirc Defense":["Pe2.Pe4","Pd7.Pd6", "Pd2.Pd4", "Ng8.Nf6"],"Giuoco Piano":["Pe2.Pe4", "Ng1.Nf3", "Nc7.Nc6", "Bf1.Bc4"],"English Opening":["Pc2.Pc4"],"Russian Game":["Pe2.Pe4", "Pe7.Pe5", "Ng1.Nf3", "Ng8.Nf6"],"Scotch Game":["Pe2.Pe4", "Pe7.Pe5", "Ng1.Nf3", "Nb8.Nc6", "Pd2.Pd4"],"Modern Defense":["Pe2.Pe4", "Pg7.Pg6"]
      }


    


    function getMoves(openingN) {
    // Check if the opening name exists in the openings object
    if (openings.hasOwnProperty(openingN)) {
        return openings[openingN];
    } else {
        // If opening name is not found, return an empty array or handle the error as needed
        return [];
    }
}

var Moves = getMoves(openingName);

</script>


 <script>  
  document.getElementById('forwardButton').addEventListener('click', function() {
     displayNextMove(Moves);
    });   
  
  document.getElementById('backwardButton').addEventListener('click', function() {
      displayPreviousMove(Moves);      
    });
</script>

</body>

</html>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chess-Game</title>
    <link rel="stylesheet" href="style.css" />
    <script defer src="./logic/mapping-initial.js"></script>
  </head>
  <body>
    <div class="dad-container">
      <div class="main-container">
        <div class="file-a files">
          <div class="a8 square">
            <img src="./images/pieces/black/rook.png" alt="" />
          </div>
          <div class="a7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="a6 square"><img src=" " alt="" /> </div>
          <div class="a5 square"><img src=" " alt="" /> </div>
          <div class="a4 square"><img src="" alt="" /> </div>
          <div class="a3 square"><img src=" " alt="" /></div>
          <div class="a2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="a1 square">
            <img src="./images/pieces/white/rook.png" alt="" />
          </div>
        </div>
        <div class="file-b files">
          <div class="b8 square">
            <img src="./images/pieces/black/knight.png" alt="" />
          </div>
          <div class="b7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="b6 square"><img src=" " alt="" /> </div>
          <div class="b5 square"><img src=" " alt="" /> </div>
          <div class="b4 square"><img src=" " alt="" /> </div>
          <div class="b3 square"><img src=" " alt="" /> </div>
          <div class="b2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="b1 square">
            <img src="./images/pieces/white/knight.png" alt="" />
          </div>
        </div>
        <div class="file-c files">
          <div class="c8 square">
            <img src="./images/pieces/black/bishop.png" alt="" />
          </div>
          <div class="c7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="c6 square"><img src=" " alt="" /> </div>
          <div class="c5 square"><img src=" " alt="" /> </div>
          <div class="c4 square"><img src=" " alt="" /> </div>
          <div class="c3 square"><img src=" " alt="" /> </div>
          <div class="c2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="c1 square">
            <img src="./images/pieces/white/bishop.png" alt="" />
          </div>
        </div>
        <div class="file-d files">
          <div class="d8 square">
            <img src="./images/pieces/black/queen.png" alt="" />
          </div>
          <div class="d7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="d6 square"><img src=" " alt="" /> </div>
          <div class="d5 square"><img src=" " alt="" /> </div>
          <div class="d4 square"><img src=" " alt="" /> </div>
          <div class="d3 square"><img src=" " alt="" /> </div>
          <div class="d2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="d1 square">
            <img src="./images/pieces/white/queen.png" alt="" />
          </div>
        </div>
        <div class="file-e files">
          <div class="e8 square">
            <img src="./images/pieces/black/king.png" alt="" />
          </div>
          <div class="e7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="e6 square"><img src=" " alt="" /> </div>
          <div class="e5 square"><img src=" " alt="" /> </div>
          <div class="e4 square"><img src=" " alt="" /> </div>
          <div class="e3 square"><img src=" " alt="" /> </div>
          <div class="e2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="e1 square">
            <img src="./images/pieces/white/king.png" alt="" />
          </div>
        </div>
        <div class="file-f files">
          <div class="f8 square">
            <img src="./images/pieces/black/bishop.png" alt="" />
          </div>
          <div class="f7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="f6 square"><img src=" " alt="" /> </div>
          <div class="f5 square"><img src=" " alt="" /> </div>
          <div class="f4 square"><img src=" " alt="" /> </div>
          <div class="f3 square"><img src=" " alt="" /> </div>
          <div class="f2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="f1 square">
            <img src="./images/pieces/white/bishop.png" alt="" />
          </div>
        </div>
        <div class="file-g files">
          <div class="g8 square">
            <img src="./images/pieces/black/knight.png" alt="" />
          </div>
          <div class="g7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="g6 square"><img src=" " alt="" /> </div>
          <div class="g5 square"><img src=" " alt="" /> </div>
          <div class="g4 square"><img src=" " alt="" /> </div>
          <div class="g3 square"><img src=" " alt="" /> </div>
          <div class="g2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="g1 square">
            <img src="./images/pieces/white/knight.png" alt="" />
          </div>
        </div>
        <div class="file-h files">
          <div class="h8 square">
            <img src="./images/pieces/black/rook.png  " alt="" />
          </div>
          <div class="h7 square">
            <img src="./images/pieces/black/pawn.png" alt="" />
          </div>
          <div class="h6 square"><img src=" " alt="" /> </div>
          <div class="h5 square"><img src=" " alt="" /> </div>
          <div class="h4 square"><img src=" " alt="" /> </div>
          <div class="h3 square"><img src=" " alt="" /> </div>
          <div class="h2 square">
            <img src="./images/pieces/white/pawn.png" alt="" />
          </div>
          <div class="h1 square">
            <img src="./images/pieces/white/rook.png" alt="" />
          </div>
        </div>
      </div>
    </div>




  </body>
</html>

