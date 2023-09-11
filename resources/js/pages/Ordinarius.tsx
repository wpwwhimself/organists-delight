import { useState, useEffect } from "react";
import { OrdinariumProps } from "../types";
import axios from "axios";
import { MassElemSection, OrdinariumProcessor, SongLyrics } from "../components/MassElements";
import { DummyInput } from "../components/Interactives";
import { SheetMusicRender } from "../components/SheetMusicRender";

export function PresentOrdinarium(){
    const color = window.location.href.replace(/.*\/present\/(.*).*/, "$1");
    const dParts = {
      "kyrie": "Kyrie",
      "gloria": "Gloria",
      "psalm": "Psalm",
      "aklamacja": "Aklamacja",
      "sanctus": "Sanctus",
      "agnus-dei": "Agnus Dei",
    }

    const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);

    useEffect(() => {
        axios.get("/api/ordinarius-data", {params: {
            color: color,
        }}).then(res => {
            setOrdinarium(res.data.ordinarium);
        });
    }, []);

    return <div className="flex-down">
      {ordinarium.map((el, key) =>
      <MassElemSection id={el.part} key={key}>
        <h1>{dParts[el.part as keyof typeof dParts]}</h1>
        <SheetMusicRender notes={el.sheet_music} />
      </MassElemSection>)}
    </div>
}
