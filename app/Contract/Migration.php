<?php

namespace App\Contract;

use Closure;
use Illuminate\Database\Migrations\Migration as LaravelMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

abstract class Migration extends LaravelMigration
{
    protected $table;

    protected Blueprint $builder;

    protected $timestamps = true;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create($this->table, function(Blueprint $table) {
            $this->builder = $table;

            $this->handle($table);

            if($this->timestamps) {
                $table->dateTime('updated_at');
                $table->dateTime('created_at');
            }
        });
    }

    public abstract function handle(Blueprint $table);

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIfExists();
    }

    /**
     * Get a schema builder instance for the default connection.
     *
     * @return Builder
     */
    protected function schema(): Builder
    {
        return Schema::connection($this->connection ?? null);
    }

    protected function create(Closure $callback)
    {
        $this->schema()->create($this->table, $callback);
    }

    protected function dropIfExists()
    {
        $this->schema()->dropIfExists($this->table);
    }
}
