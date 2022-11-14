<?php

namespace Application\Lib;

class Validator implements TwigImplementer
{
    private array $params;

    private string $errorMessage = '';

    private string $methodName;

    private string|null $argument = null;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function validate(): bool
    {
        unset($_SESSION['data']['user']['errorMessage']);
        unset($_SESSION['data']['errorMessage']);
        foreach ($this->params as $param => $implodedRules) {
            $rules = explode('|', $implodedRules);

            if (in_array('required', $rules)) {
                if (!$this->checkRequiredParam($param, $rules)) {
                    continue;
                }
            } else {
                if (!isset($_POST[$param])) continue;
            }

            $this->runRules($rules, $param);
            $this->createSuccessSession($param);
        }
        return $this->isErrorsEmpty();
    }

    private function checkRequiredParam($param, &$rules): bool
    {
        unset($rules[array_search('required', $rules)]);
        if (!isset($_POST[$param])) {
            $this->errorMessage = "$param fields is required.";
            $this->createErrorSession($param);
            return false;
        }
        return true;
    }

    private function runRules(array $rules, string $param): void
    {
        foreach ($rules as $rule) {
            $this->methodName = $rule;
            $this->getMethodAndArgument($rule);
            $this->callMethodAndCheckValid($param);
        }
    }

    private function getMethodAndArgument(string $rule): void
    {
        if (str_contains($rule, ':')) {
            $methodData = explode(':', $rule);
            $this->methodName = $methodData[0];
            $this->argument = $methodData[1];
        }
    }

    private function callMethodAndCheckValid(string $param): void
    {
        $methodName = $this->methodName;
        $isValid = self::$methodName($_POST[$param], $this->argument);

        if (!$isValid) {
            $this->createErrorSession($param);
        }
    }

    private function createErrorSession(string $param): void
    {
        if (isset($_SESSION['data']['errorMessage'][$param])) {
            $_SESSION['data']['errorMessage'][$param][] = $this->errorMessage;
        } else {
            $_SESSION['data']['errorMessage'][$param] = [$this->errorMessage];
        }
    }

    private function createSuccessSession(string $param): void
    {
        if (!isset($_SESSION['data']['errorMessage'][$param])) {
            $_SESSION['data']['correctField'][$param] = $_POST[$param];
        }
    }

    private function isErrorsEmpty(): bool
    {
        $res = empty(($_SESSION['data']['errorMessage']));
        $this->clearSessionData($res);
        return $res;
    }

    private function clearSessionData(bool $result): void
    {
        if ($result) {
            unset($_SESSION['data']['correctField']);
            unset($_SESSION['data']['errorMessage']);
        }
    }

    private function upperCase(string $param, int $value = null): bool
    {
        if ((preg_match_all('/[A-Z]/', $param)) < $value) {
            $this->errorMessage = "Your string must contain " . $value . " uppercase character.";
            return false;
        }
        return true;
    }

    private function lowerCase(string $param, int $value = null): bool
    {
        if ((preg_match_all('/[a-z]/', $param)) < $value) {
            $this->errorMessage = "Your string must contain " . $value . " lowercase character.";
            return false;
        }
        return true;
    }

    private function checkDigit(string $param, int $value = null): bool
    {
        if ((preg_match_all('/[0-9]/', $param)) < $value) {
            $this->errorMessage = "Your string must contain " . $value . " digit character.";
            return false;
        }
        return true;
    }

    private function specialCharacter(string $param, int $value = null): bool
    {
        if ((preg_match_all("/[\[^\'£$%^&*()}{@:\'#~?><>,;@\|\-_+\-¬\`\]]/", $param)) < $value) {
            $this->errorMessage = "Your string must contain " . $value . " special character.";
            return false;
        }
        return true;
    }

    private function min(string $param, int $value = null): bool
    {
        if (strlen($param) < $value) {
            $this->errorMessage = "Your string must to be length " . $value . ".";
            return false;
        }
        return true;
    }

    private function onlyString(string $param): bool
    {
        if (!preg_match('/^[A-z]*$/', $param)) {
            $this->errorMessage = "Your string must contain only letters.";
            return false;
        }
        return true;
    }

    private function onlyInt(int $param): bool
    {
        if (!preg_match('/^[0-9]+$/', $param)) {
            $this->errorMessage = "This field must contain only numbers.";
            return false;
        }
        return true;
    }

    private function minValue(int $param, int $value): bool
    {
        if($param < $value) {
            $this->errorMessage = "This field must be greater than " . $value . ".";
            return false;
        }
        return true;
    }

    private function isEmail(string $param): bool
    {
        if (!filter_var($param, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = "Your email is not correct.";
            return false;
        }
        return true;
    }

    private function isMobilePhone(string $param): bool
    {
        if(!preg_match('/^[0-9]{11,11}+$/', $param)) {
            $this->errorMessage = "This field not must contain mobile phone.";
            return false;
        }
        return true;
    }

    private function emailExist(string $param, string $value): bool
    {
        $row = $this->getMethodClass($param, $value);

        if ($row['total'] > 0) {
            $this->errorMessage = "Field with this name already in use.";
            return false;
        }
        return true;
    }

    private function isEqualTo(string $param, string $value): bool
    {
        if ($param != $value) {
            $this->errorMessage = "Fields are not equal.";
            return false;
        }
        return true;
    }

    private function findUserEmail(string $param, string $value): bool
    {
        $row = $this->getMethodClass($param, $value);

        if ($row['total'] < 1) {
            $this->errorMessage = "This email is not found.";
            return false;
        }
        return true;
    }

    private function getMethodClass(string $param, string $value): array
    {
        $data = explode(',', $value);
        $dbField = $data[1];
        $model = new $data[0];
        return $model->getUsersEmail([$dbField => $param]);
    }

    public function addFunctions(&$twig)
    {
        $twig->addGlobal('session', $_SESSION);
    }
}
