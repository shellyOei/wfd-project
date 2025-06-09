<?php

namespace App;

interface UserRepositoryInterface
{
    public function all();

    public function allBy($filter);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
