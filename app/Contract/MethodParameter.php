<?php

namespace App\Contract;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Validation\ValidationException;

abstract class MethodParameter
{
    protected array $data = [];

    protected Validator $validator;

    abstract public function rules(): array;

    public function __call($method, $args)
    {
        [$method, $name] = Str::of($method)
            ->camel()
            ->whenStartsWith(
                ['get', 'set', 'has'],
                function (Stringable $string) {
                    return [
                        $method = $string->snake()->split('_')->first(),
                        $string->after($method)->camel()
                    ];
                },
                fn() => [null, null]
            );

        if ($method === null) {
            return $this;
        }

        return call_user_func([$this, $method], $name, ...$args);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        Arr::set($this->data, $name, $value);

        return $this;
    }

    /**
     * @param string|int $name
     * @param mixed $default
     * @return void
     */
    public function get($name, $default = null)
    {
        return Arr::get($this->data, $name, $default);
    }

    /**
     * @param string|array $name
     * @return bool
     */
    public function has($name)
    {
        return Arr::has($this->data, $name);
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    public function customerAttributes(): array
    {
        return [];
    }

    protected function getValidate()
    {
        if (!isset($this->validator)) {
            $this->validator = validator(
                $this->data,
                $this->rules(),
                $this->messages(),
                $this->customerAttributes()
            );
        }
        return $this->validator;
    }

    /**
     * @param bool $throwOnFail
     * @return bool
     * @throws ValidationException
     */
    public function verify($throwOnFail = true)
    {
        $fail = $this->getValidate()->fails();

        if ($fail && $throwOnFail) {
            throw new ValidationException($this->getValidate());
        }

        return !$fail;
    }
}
