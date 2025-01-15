<?php

require_once "vendor/autoload.php";

$dir = __DIR__ . '/grids';
$files = ['grid1.json', 'grid2.json', 'grid3.json', 'grid4.json', 'unsolvable.json'];

foreach ($files as $file) {
    echo "Test avec $file:\n";
    $filepath = $dir . '/' . $file;

    $grid = SudokuGrid::loadFromFile($filepath);
    echo "Grille initiale:\n";
    echo $grid->display() . "\n";

    $startTime = microtime(true);
    $solvedGrid = SudokuSolver::solve($grid, 0, 0);

    if ($solvedGrid === null) {
        echo "Pas de solution trouvée\n";
    } else {
        echo "Solution:\n";
        echo $solvedGrid->display();
    }

    $duration = round((microtime(true) - $startTime) * 1000);
    echo "Temps de résolution: $duration ms\n";
    echo "---------------------------------\n\n";
}
