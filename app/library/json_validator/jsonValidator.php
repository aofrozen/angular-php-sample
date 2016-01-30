<?php

namespace app\library\json_validator;

use JsonSchema;

class JsonValidator
    {
        public function check($schema, $decode_json)
            {
                $validator = new JsonSchema\Validator();
                $decode_schema = $this->getSchema($schema);
                $validator->check($decode_json, $decode_schema);
                if ($validator->isValid())
                    {
                        //echo "The supplied JSON validates against the schema.\n";
                        return true;
                    } else
                    {
                        /*echo "JSON does not validate. Violations:\n";
                        foreach ($validator->getErrors() as $error) {
                            echo "<b>[{$error['property']}] {$error['message']}</b>\n";
                        }*/
                        return false;
                    }
            }

    /* Schemas */
        private function getSchema($schema)
            {
                $schema = file_get_contents(dirname(__DIR__) . '/json_validator/schema/' . $schema . '.json');
                return json_decode($schema);
            }
    }