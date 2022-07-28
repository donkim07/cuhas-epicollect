<?php

namespace ec5\Http\Validation\Entries\Upload\InputRules;

use ec5\Models\Entries\EntryStructure;
use ec5\Models\Projects\Project;
use Log;
use DateTime;

class RuleDateInput extends RuleInputBase
{

    /**
     * @param $inputDetails
     * @param string|array $answer
     * @param Project $project
     */
    public function setRules($inputDetails, $answer, Project $project)
    {
        // Set rules based on the input details
        // Source will be the input ref

        // Check date is in correct format
        $this->rules[$inputDetails['ref']] = ['date'];

        // Set remaining rules in the parent class
        parent::setRules($inputDetails, $answer, $project);

    }

    public function additionalChecks($inputDetails, $answer, Project $project, EntryStructure $entryStructure)
    {

        //if this question is not required, skip extra checks
        if($inputDetails['is_required'] === false && $answer === '') {
            return $answer;
        }

        //ISO 8601 format only -> 1977-05-22T00:00:00.000
        $regex = '/([0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3})+/';
        if (!preg_match_all($regex, $answer)) {
            Log::error('Date wrong format uploaded - regex failed', [
                'project slug' => $project->slug,
                'date' => $answer
            ]);
            $this->errors[$inputDetails['ref']] = ['ec5_79'];
        }

        //valid date?
        if (!strtotime($answer)) {
            Log::error('Date wrong format uploaded - strtotime failed', [
                'project slug' => $project->slug,
                'date' => $answer
            ]);
            $this->errors[$inputDetails['ref']] = ['ec5_79'];
        }

        //Let's check if Y-m-d is actually a valid date in the history of time
        $datePart = '';
        try {
            $datePart = explode('T', $answer)[0];
        }
        catch (\Exception $e) {
            Log::error('Date wrong format uploaded - validateDate failed', [
                'project slug' => $project->slug,
                'date' => $answer
            ]);
            $this->errors[$inputDetails['ref']] = ['ec5_79'];
        }

        if(!$this->validateDate($datePart)) {
            Log::error('Date wrong format uploaded - validateDate failed', [
                'project slug' => $project->slug,
                'date' => $answer
            ]);
            $this->errors[$inputDetails['ref']] = ['ec5_79'];
        }

        return $answer;
    }

    //see t.ly/YEox
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
