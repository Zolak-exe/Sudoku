<?php
class SudokuGrid implements GridInterface
{
    public array $data;

    public static function loadFromFile($filepath): ?SudokuGrid
    {
        if (!file_exists($filepath)) {
            return null;
        }
        $data = json_decode(file_get_contents($filepath), true);
        return new self($data);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get(int $rowIndex, int $columnIndex): int
    {
        return $this->data[$rowIndex][$columnIndex];
    }

    public function set(int $rowIndex, int $columnIndex, int $value): void
    {
        $this->data[$rowIndex][$columnIndex] = $value;
    }

    public function row(int $rowIndex): array
    {
        return $this->data[$rowIndex];
    }

    public function column(int $columnIndex): array
    {
        $column = [];
        for ($i = 0; $i < 9; $i++) {
            $column[] = $this->data[$i][$columnIndex];
        }
        return $column;
    }

    public function square(int $squareIndex): array
    {
        $startRow = intdiv($squareIndex, 3) * 3;
        $startCol = ($squareIndex % 3) * 3;
        $square = [];

        for ($i = $startRow; $i < $startRow + 3; $i++) {
            for ($j = $startCol; $j < $startCol + 3; $j++) {
                $square[] = $this->data[$i][$j];
            }
        }

        return $square;
    }

    public function display(): string
    {
        $output = "";
        for ($i = 0; $i < 9; $i++) {
            if ($i > 0 && $i % 3 == 0) {
                $output .= str_repeat('-', 25) . "\n";
            }
            for ($j = 0; $j < 9; $j++) {
                if ($j > 0 && $j % 3 == 0) {
                    $output .= "| ";
                }
                $value = $this->data[$i][$j];
                $output .= ($value === 0 ? '_' : $value) . " ";
            }
            $output .= "\n";
        }
        return $output;
    }

    public function isValueValidForPosition(int $rowIndex, int $columnIndex, int $value): bool
    {
        if (in_array($value, $this->row($rowIndex))) {
            return false;
        }

        if (in_array($value, $this->column($columnIndex))) {
            return false;
        }

        $squareIndex = 3 * intdiv($rowIndex, 3) + intdiv($columnIndex, 3);
        if (in_array($value, $this->square($squareIndex))) {
            return false;
        }

        return true;
    }

    public function getNextRowColumn(int $rowIndex, int $columnIndex): array
    {
        $nextCol = ($columnIndex + 1) % 9;
        $nextRow = $rowIndex + ($nextCol == 0 ? 1 : 0);
        return [$nextRow, $nextCol];
    }

    public function isValid(): bool
    {
        for ($i = 0; $i < 9; $i++) {
            $row = array_filter($this->row($i), fn($x) => $x !== 0);
            if (count($row) !== count(array_unique($row))) {
                return false;
            }
        }

        for ($i = 0; $i < 9; $i++) {
            $col = array_filter($this->column($i), fn($x) => $x !== 0);
            if (count($col) !== count(array_unique($col))) {
                return false;
            }
        }

        for ($i = 0; $i < 9; $i++) {
            $square = array_filter($this->square($i), fn($x) => $x !== 0);
            if (count($square) !== count(array_unique($square))) {
                return false;
            }
        }

        return true;
    }

    public function isFilled(): bool
    {
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if ($this->data[$i][$j] === 0) {
                    return false;
                }
            }
        }
        return true;
    }
}
