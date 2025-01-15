<?php

class SudokuSolver implements SolverInterface
{
    public static function solve(SudokuGrid $grid, int $rowIndex, int $columnIndex): ?SudokuGrid
    {
        if ($rowIndex >= 9) {
            return $grid;
        }

        if ($grid->get($rowIndex, $columnIndex) !== 0) {
            list($nextRow, $nextCol) = $grid->getNextRowColumn($rowIndex, $columnIndex);
            return self::solve($grid, $nextRow, $nextCol);
        }

        for ($value = 1; $value <= 9; $value++) {
            if ($grid->isValueValidForPosition($rowIndex, $columnIndex, $value)) {
                $newGrid = clone $grid;
                $newGrid->set($rowIndex, $columnIndex, $value);

                list($nextRow, $nextCol) = $newGrid->getNextRowColumn($rowIndex, $columnIndex);
                $result = self::solve($newGrid, $nextRow, $nextCol);

                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }
}
