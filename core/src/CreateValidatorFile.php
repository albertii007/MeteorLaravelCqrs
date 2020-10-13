<?php

namespace MeteorCqrs\Meteor\src;

trait CreateValidatorFile
{
    private function CreateValidatorFile($validator, $parsedPath, $filePath)
    {
        $validatorClass = class_basename($validator);

        $namespace = implode('\\', explode('\\', $parsedPath, -1));

        $validatorParsedPath = $this->changeName($filePath);

        $this->writeValidatorFile($validatorParsedPath, $namespace, $validatorClass);

        dd($validatorClass . ' must be implemented!');
    }

    public function changeName($filePath){
            $validator = substr($filePath, 0, strpos($filePath, 'Handler')) . 'Validator.php';
            return $validator;
       
    }

    private function writeValidatorFile($validatorpath, $namespace, $validatorClass)
    {
        $handle = fopen($validatorpath, 'a+') or die('Cannot open file: ' . $validatorpath);

        $content = file_get_contents(__DIR__ . '/../view/Validator.php', false);
        $newContent = str_replace('//', '', $content);
        $newContent = str_replace('##namespace', 'namespace ' . $namespace . ';', $newContent);
        $newContent = str_replace('##ClassName', $validatorClass, $newContent);
        $newContent = str_replace('##dd', 'dd("' . $validatorClass . ' must be implemented!");', $newContent);

        fwrite($handle, $newContent);

        fclose($handle);
    }
}
