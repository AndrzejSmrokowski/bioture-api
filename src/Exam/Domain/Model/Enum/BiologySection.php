<?php

namespace Bioture\Exam\Domain\Model\Enum;

enum BiologySection: string
{
    case CHEMISTRY_OF_LIFE = 'chemistry_of_life';              // woda, białka, lipidy, cukry, witaminy itd.
    case CELL_BIOLOGY = 'cell_biology';                        // budowa komórki, organella, podziały komórkowe
    case METABOLISM = 'metabolism';                            // fotosynteza, oddychanie, enzymy
    case GENETICS = 'genetics';                                // DNA, geny, dziedziczenie, mutacje
    case EVOLUTION = 'evolution';                              // dobór naturalny, dryf, specjacja
    case DIVERSITY_OF_ORGANISMS = 'diversity_of_organisms';    // systematyka, grupy zwierząt, roślin, grzybów
    case HUMAN_PHYSIOLOGY = 'human_physiology';                // układy: nerwowy, krwionośny, hormonalny itd.
    case PLANT_BIOLOGY = 'plant_biology';                      // anatomia i fizjologia roślin
    case ECOLOGY = 'ecology';                                  // populacje, ekosystemy, sukcesja, ochrona środowiska
    case BIOTECHNOLOGY = 'biotechnology';                      // inżynieria genetyczna, GMO, terapie, biotechnologia tradycyjna
    case METHODS = 'methods';                                  // doświadczenia, planowanie badań, analiza wyników
}
