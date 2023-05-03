<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\OrdinariusColor;
use App\Models\Set;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
    public function sets(){
        $formulas = Formula::all();
        $sets = [];
        foreach($formulas as $formula){
            $sets[$formula->name] = $formula->user_sets;
        }

        return view("sets", array_merge(
            ["title" => "Dostępne zestawy"],
            compact("formulas", "sets")
        ));
    }

    public function setPresent($set_id){
        $set = Set::findOrFail($set_id);

        return view("set-present", array_merge(
            ["title" => $set->name],
            compact("set")
        ));
    }

    public function set($set_id){
        $set = Set::findOrFail($set_id);
        $formulas = Formula::all()->pluck("name", "name");
        $colors = OrdinariusColor::all()->pluck("display_name", "name");
        $songs = Song::all()->pluck("title", "title");

        return view("set", array_merge(
            ["title" => $set->name . " | Edycja mszy"],
            compact("set", "formulas", "colors", "songs")
        ));
    }

    public function setEdit(Request $rq){
        if($rq->action === "update"){
            Set::findOrFail($rq->id)->update([
                "name" => $rq->name,
                "formula" => $rq->formula,
                "color" => $rq->color,
                "public" => $rq->has("public"),
                "sIntro" => $rq->sIntro,
                "sOffer" => $rq->sOffer,
                "sCommunion" => $rq->sCommunion,
                "sAdoration" => $rq->sAdoration,
                "sDismissal" => $rq->sDismissal,
                "pPsalm" => $rq->pPsalm,
                "pAccl" => $rq->pAccl,
            ]);
            $response = "Msza poprawiona";
        }elseif($rq->action === "delete"){
            Set::findOrFail($rq->id)->delete();
            $response = "Msza usunięta";
        }

        return redirect()->route("sets")->with("success", $response);
    }

    public function setAdd(){
        $new_set = Set::create([
            "user_id" => Auth::id(),
            "public" => false,
            "name" => "--Nowa msza--",
            "formula" => "zwykła",
            "color" => "green",
        ]);

        return redirect()->route("set", ["set_id" => $new_set])->with("success", "Szablon utworzony, dodaj mszę");
    }
}
