<?php
namespace App\Models;

class CsvParser {
    public static function parse(string $filepath): array {
        $questions = [];
        if (($handle = fopen($filepath, 'r')) !== false) {
            // Skip header if present
            $firstLine = fgetcsv($handle);
            if ($firstLine && strtolower($firstLine[0]) !== 'question') {
                rewind($handle);
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3) continue; // Need at least question, correct, 1 wrong
                
                $question = trim($row[0]);
                $correctAnswer = trim($row[1]);
                $wrongOptions = array_map('trim', array_slice($row, 2));
                $wrongOptions = array_filter($wrongOptions); // Remove empty values

                if ($question && $correctAnswer && count($wrongOptions) > 0) {
                    $questions[] = [
                        'question' => $question,
                        'correct_answer' => $correctAnswer,
                        'wrong_options' => $wrongOptions
                    ];
                }
            }
            fclose($handle);
        }
        return $questions;
    }
}
