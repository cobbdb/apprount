<?php

/**
 * Login test for MW bot.
 */

require_once "./private/API.php";
require_once "./private/Console.php";


$titleCollection = <<<STR
Portal:Energy|Category:Global_Health_Medical_Device_Compendium|Energy|User:RichardF/Index of energy articles|User:RichardF/Outline of energy|A Bibliography for the Solar Home Builder|A Chinese Biogas Manual|A Cooking Place for Large-Sized Pots|A Design Manual for Water Wheels|A Sitting Handbook for Small Wind Energy Conversion Systems|A Solar Water Heater Workshop Manual|A State of the Art Survey of Solar Powered Irrigation Pumps Solar Cookers and Woodburning Stoves|A Survey of the possible Use of Windpower in Thailand and the Philippines|A Woodstove Compendium|Agave for Biomass and Biofuel|Alpha radiation|Alternating current|Aluminum|An Attached Solar Greenhouse|Appropriate use of lead-acid batteries|Arcata Green Team|Aspects of Irrigation with Windmills|AT CAD Team/AT squirrelcage rotor induction motor|AT CAD Team/Solar updraft tower|Australian carbon tax|Barrel Biodigester|Basic Principles of Passive Solar Design|Beta radiation|Binding energy|Biogas and Waste Recycling|Biogas Handbook|Biogas Plants in Animal Husbandry|Biogas Systems in India|Biogas Technology in the Third World|Biomass|Biomass (original)|Boiling water reactor|Bread Box Water Heater Plans|Breakdown voltage|Brief Notes on the Design and Construction of Wood-burning Cook-stoves|Burning Issues|Carbon nanotubes and the hydrogen fuel cell|Carbon pricing literature review|Cattail Chemurgy|CCAT Bubble Box|Charcoal|Charcoal Making for Small Scale Enterprises|Charcoal Production Using a Transportable Metal Kiln|Combined heat and power system|Community climate action plan|Comparing Simple Charcoal Production Technologies for the Caribbean|Comparison of alternative ICE fuels|Comparison of bladed rotors for HECS|Comparison of IC motors|Comparison of Improved Stoves|Comparison of motors|Compost Fertilizer and Biogas Production from Human and Farm Wastes in the People's Republic of China|Compressed air|Compressed air energy storage and use system|Conductor|Considerations for the Use of Wind Power for Borehole Pumping|Construction Manual for a Cretan Windmill|Construction Manual for PU350 and PU500 Windmills|Cook-stove Construction by the TerraCETA Method|Cost Reduction Considerations in Small Hydropower Equipment|Crystal|Decommissioning|Deep-cycle lead-acid batteries for renewable energy storage|Design for a PedalDriven Power Unit for Transport and Machine Uses in Developing Countries|Design of CrossFlow Turbine BYS/T1|Design of CrossFlow Turbine BYS/T3|Design of Small Water Storage and Erosion Control Dams|Design of Small Water Turbines for Farms and Small Communities|Designing a Test Procedure for Domestic Wood-burning Stoves|Diesel engine|Diesel Engines (original)|Direct band gap|Directory of Manufacturers of Small Hydropower Equipment|Distributed generation|Diverting indirect subsidies from the nuclear to the photovoltaic industry: Energy and financial returns|Domestic energy consumption|Double Drum Sawdust Stove|Duckweed|Efficiency|Electric Power from the Wind|Electrical conductivity|Electricity|Electron mobility|Electrons|Electroplating|Elements of Solar Architecture for Tropical Regions|Embedded energy|Embodied energy|Emissions trading|Energie aus Abwasser|Energy and quality of life|Energy cannibalism|Energy CBSM|Energy content of fuels|Energy currency|Energy EASE 2007|Energy efficiency|Energy for Rural Development|Energy for Rural Development (Supplement)|Energy from the Wind|Energy from wastewater|Energy policy|Energy star|Energy use|Energy: The Solar Prospect|Energía solar fotovoltaica|ESCO|First law of thermodynamics|Fission|Fixed dome digester|Food from Windmills|Food or Fuel|Foot Power|Foro Para el Desarollo Sostentable Stove Dissemination Program|Fossil fuels|From Lorena to a Mountain of Fire|Fuel|Fuel Alcohol Production|Fuel cells|Fuel from Farms|Fuel Gas from Cow Dung|Gallium arsenide solar cells|Gamma radiation|Gasification|Gaviotas tropical windmill|Gemini Synchronous Inverter Systems|Germanium|Green Building Pre-Apprenticeship Program/Energy Fundamentals|Green Power Equivalency Calculator|Original:Grid connection|Grid connection|Ground source heat pumps|Guidelines on Evaluating the Fuel Consumption of Improved Cookstoves|Harnessing Water Power for Home Energy|Heat distribution test|Heat engine|Heat engines|Helping People in Poor Countries Develop FuelSaving Cookstoves|Hints on the Development of Small WaterPower|Homegrown Sundwellings|Homemade 6Volt WindElectric Plants|Horizontal Axis Fast Running Wind Turbines for Developing Countries|Hot Water (Morgan, Morgan, Taylor and Taylor)|House insulation|How Nanotechnology is Improving the Hydrogen Fuel Cell|How to Build a "Cretan Sail" Windpump for Use in LowSpeed Wind Conditions|How to Build a Mechanically Powered Battery Charger for LED Lighting|How to Build an Oil Barrel Stove|How to Construct a Cheap Wind Machine for Pumping Water|HSU Dorm Cogeneration|Humboldt County energy services|Humboldt State University Paper Towel Diversion Project Phase II|Hybrid fuel cell|Portal:Hybrid power systems|Hydro-car|Hydro-power|Hydrogen from water and sun|Hydrogen fuel cell|Hydroxymethylfurfural (HMF)|Independent Energy|Industrial Archaeology of Watermills and Waterpower|Insulator|Kamen's Stirling motor|User:KateMonroe|Kerosene and liquid petroleum gas|Original:Kerosene and Liquid Petroleum Gas (LPG)|User:KVDP/Appropriate energy overview|Lab Tests of Fired Clay Stoves the Economics of Improved Stoves and Steady State Heat Loss from Massive Stoves|Laboratory and Field Testing of Monolithic Mud Stoves|Laurel Tree Charter School energy use visualization|Laws of thermodynamics|Lead-acid battery construction|Less Smoky Rooms|Levelised Cost of Electricity Literature Review|Life cycle analyses of energy technologies Literature review|Lifespan and Reliability of Solar Photovoltaics - Literature Review|Limitations of nuclear power as a sustainable energy source|Limits of energy availability and efficiency|Local Experience with MicroHydro Technology|Lorena Owner-Built Stoves|Low Cost Development of Small Water Power Sites|Low Cost Passive Solar Greenhouses|Low Cost Wind Speed Indicator. Brace Research Institute|Low Cost Windmill for Developing Nations|Manege: Animal-Driven Power Gear|Manganese|Manual for the Design of a Simple Mechanical Water-Hydraulic Speed Governor|Matching of Wind Rotors to Low Power Electrical Generators|Measuring DC electrical energy production from micro-renewables|Metalorganic vapour phase epitaxy|Metalorganic Vapour Phase Epitaxy|Micro - Hydropower Schemes in Pakistan|Micro Hydro electric power|Micro Pelton Turbines|Microbial fuel cells|MicroHydro Power: Reviewing an Old Concept|MicroHydro: Civil Engineering Aspects|Microhydropower Handbook Volume 233|Microhydropower Handbook Volume 234|MicroHydropower Sourcebook|Microturbine|Mill Drawings|Mini Hydro Power Stations|Model Boilers and Boilermaking|Model Stationary and Marine Steam Engines|Modern Stoves for All|Modified Justa Stove|Movements of liquids and gases in pumps and motors|Multi-Purpose Power Unit with Horizontal Water Turbine: Basic Information|MultiPurpose Power Unit with Horizontal Water Turbine: Operation and Maintenance Manual|Nega-watts|Nepal: Private Sector Approach to Implementing MicroHydropower Schemes|Net metering|New Himalayan Water Wheel|New Nepali Cooking Stoves|Nitrous oxide|Nuclear energy|Ocean current|Oil dependency|On Watermills in Central Crete|One Pot Two Pot...Jackpot|Optimization and Characteristics of a Sailwing Windmill Rotor|Otros Mundos Chiapas Stove Dissemination Program|Overshot and Current Water Wheels|P-N Junction|Patsari Cookstove|Pedal Power: In Work Leisure and Transportation|Pelton Micro-Hydro Prototype Design|Performance Test of a Savonius Rotor|Petroleum|PH261|Photovoltaics|Piston Water Pump|Plant fats and oils|Polyethylene tube digester|Power|Power and energy basics|Power and energy hall of shame|Proceedings of the Conference on Energy-Conserving, Solar-Heated Greenhouses|Proceedings of the Meeting of the Expert Working Group on the Use of Solar and Wind Energy|Pronatura Chiapas Stove Dissemination Program|Quality of life through design|Radiation|Raintree-Foundation|Rays of Hope|RCEA CAN YOU WorKIT|Reaching Up, Reaching Out: A Guide to Organizing Local Solar Events|Reaction turbine|Reichstag|Renewable energy|Renewable Energy Research in India|Renewable Energy Resources and Rural Applications in the Developing World|Renewable Sources of Energy: Biogas|Report on the Design and Operation of a Full-Scale Anaerobic Dairy Manure Digester|Report on Training of District Extensionists|Repower America|Repower America (deutsche Version)|RES Anatolia|Rice Husk Conversion to Energy|Rice Husks as a Fuel|Rotor Design for Horizontal Axis Windmills|Running a Biogas Programme|Original:Rural Electrification Systems|Rural lighting|Safe nuclear power|Sahores Windmill Pump|Saturated electron velocity|Savonius Rotor Construction: Vertical Axis Wind Machines from Oil Drums|Sawdust Burning Space-Heater Stove|Selecting Water-Pumping Windmills|Semiconductor|Set of Construction Drawings for PU350 and PU500 Windmills|Silage|Silicon|Simplified Wind Power Systems for Experimenters|Small Earth Dams|Small Hydroelectric Powerplants|Small Hydropower for Asian Rural Development|Small Michell (Banki) Turbine: A Construction Manual|Small Scale Hydropower Technologies|Small Scale Renewable Energy Resources and Locally Feasible Technology in Nepal|Small wind turbine|Solar (PV) Refrigeration of Vaccines (original)|Solar Cooking and Health|Solar Cooking and Health (original)|Solar Dwelling Design Concepts|Solar energy|Solar Photovoltaic (PV) Energy|Solar Photovoltaic Products - A Guide for Development Workers|Solar power in Kaohsiung City, Taiwan|Solar power in space|Solar Powered Electricity|Solar Water Heaters in Nepal|Solar Water Heating|Solar Water Heating (original)|Spin coater|Spin coating|Splitting Firewood|Steam Power|Stoves for Institutional Kitchens|Stoves for Institutional Kitchens (original)|Substrate|Sustainable Biomass in Mendocino County|Sustainable Development Commission|Portal:Sustainable energy storage|Sustainable power from heat engines|Syllabus for Irrigation with Windmills|Technical Report dealing with the TOOL Windmill Projects 1977-1981|Technology for Solar Energy Utilization|Technology Markets and People: The use and misuse of fuel stoves|Testing the Efficiency of Wood-Burning Cookstoves|Testing Timber for Moisture Content|The Anaerobic Digestion of Livestock Wastes to Produce Methane|The Banki Water Turbine|The Biogas/Biofertilizer Business Handbook|The Complete Book of Heating with Wood|The Construction Installation and Operation of an Improved Pit-Kiln for Charcoal Production|The Construction of a Transportable Charcoal Kiln|The Dhading MicroHydropower Plant: 30kWe|The Economics of Renewable Energy Systems for Developing Countries|The Food and Heat Producing Solar Greenhouse|The Fuel Savers - A Kit of Solar Ideas for Existing Homes|The Gaudgaon Village Sailwing Windmill|The Haybox|The Heat Generator|The Homebuilt WindGenerated Electricity Handbook|The Homemade Windmills of Nebraska|The New Solar Home Book|The Passive Solar Energy Book|The Planning Installation and Maintenance of Low-Voltage Rural Electrification Systems and Subsystems|The Power Guide|The Segner Turbine: A low-cost Solution for Harnessing Water Power on a very Small Scale|The SocioEconomic Context of Fuelwood Use in Small Communities|The Solar Cookery Book|The Solar Energy Timetable|The Solar Greenhouse Book|The Solar Survey|The Use of Pedal Power for Agriculture and Transport in Developing Countries|The Wind Power Book|Three Mile Island|Tides|Trees as an Indicator of Wind Power Potential|Uranium|Uranium mining|Template:User Emissions Trading|Using solid state lighting|Vegetation as an Indicator of High Wind Velocity|Vertical Axis Sail Windmill Plans|Water Power for the Farm|Watermills with Horizontal Wheels|Wind power|Wind Power for Farms, Homes and Small Industry|Window Box Solar Collector Design|Windpower in Eastern Crete|Windpumping: A Handbook: World Bank Technical Paper Number 101|Windpumps for Irrigation|Wood Conserving Cook Stoves Bibliography|Wood Conserving Cook Stoves: A Design Guide|Wood gas as fuel|Wood Stoves: How to Make and Use Them|Working Group on Development Techniques|Young Mill-Wright and Miller's Guide|Your Own Water Power Plant|Zero-point energy|Category:Alternative energy|Category:Electricity|Category:Energy conversion|Category:Energy organizations|Category:Energy policy|Category:Energy production|Category:Energy transition|Category:Energy use|Category:Engr370 Energy, Technology and Society|Category:PH261|Category:Sustainable farm energy alternatives|Category:Users interested in energy|Category:Appropedia books on energy
STR;
$idCollection = "7615|7754|8968|10335|10337|10338|10340|11109|12059|12335|12341|16512|16870|17344|20674|23278|23289|23290|23301|23305|23345|23520|23544|23550|23566|23595|23611|23612|23613|23630|23633|23637|23639|23640|23641|23642|23645|23864|28020|28522|31227|31386|31902|32435|33339|34175|34629|34630|40565|44649|234234|23423423|1233123";


class Debug {
    public static function log($str, $pre = 0, $post = 1) {
        $msg = "";
        for ($i = 0; $i < $pre; $i += 1) {
            $msg .= "<br/>";
        }
        $msg .= $str;
        for ($i = 0; $i < $post; $i += 1) {
            $msg .= "<br/>";
        }
        echo $msg;
    }
    
    public static function query($msg, $data, $header = null) {
        Debug::log("***************************************", 3);
        Debug::log("** $msg...");
        $res = API::query($data, $header);
        Debug::log($res["debug"]);
    }
}


Debug::query("Initial query on big data (ids)", Array(
    "format" => "php",
    "action" => "query",
    "prop" => "info",
    "pageids" => $idCollection
), Array());


Debug::log("***************************************", 3);
Debug::log("** Logging in...");
$debug = API::login();
Debug::log($debug);


Debug::query("Query on titles information", Array(
    "format" => "php",
    "action" => "paraminfo",
    "pagesetmodule" => "titles"
));

/* Debug::query("Query on account rights", Array(
    "format" => "php",
    "action" => "query",
    "meta" => "userinfo",
    "uiprop" => "rights"
));

Debug::query("Query on current session", Array(
    "format" => "php",
    "action" => "query",
    "meta" => "userinfo"
)); */


Debug::query("Second query on big data (ids)", Array(
    "format" => "php",
    "action" => "query",
    "prop" => "info",
    "pageids" => $idCollection
));

Debug::query("Logging out", Array(
    "format" => "php",
    "action" => "logout"
));
















