<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            //# Metodo esplicito
            //* Prima creo la colonna
            // $table->unsignedBigInteger('category_id')->nullable()->after('id');

            //* Poi creo la relazione, detta constraint
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            //# Metodo sintetico (applicabile solo quando si rispettano le convenzioni sui nomi!)
            $table->foreignId('category_id')->after('id')->nullable()->onDelete('set null')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Elimino prima il vincolo
            $table->dropForeign('posts_category_id_foreign');
            // E poi elimino la colonna
            $table->dropColumn('category_id');
        });
    }
}
