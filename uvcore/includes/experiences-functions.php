<?php

/*Get Experiences Date Selector Filter
    Returns: Prints html date control for experiences
*/
function uws_get_experiences_date_filter($uvargs = ""){
    global $uws_core_lib, $uws_today;

    $uvdate = uws_get_arg($uvargs, "date", $uws_today);

    $uvinitialddate = date("F j, Y", strtotime($uvdate));
    $uvmaxdate = date("Y-m-d", strtotime($uvdate . " +3 months"));

    $uvcontrolshtml = "
        <div class='uws-experiences-controls uws-integration' data-mindate='$uws_today' data-date='$uvdate' data-maxdate='$uvmaxdate'>
            <div class='uwsdatesel'>
                <div class='uws-dropdown-cont uwshascalincon'>
                    <i class='uwsicon-calendar'></i>
                    <a href='#uws-opendateselection' class='uwsjs-trigger-dropdown' aria-label='Select Experiences Date'><span class='uwsdy-dropvalue'>$uvinitialddate</span></a>
                    <div class='uws-dropdown'>
                        <div class='uwsfilterexpdate uws-dp-experiences-date'></div>
                    </div>
                </div>
            </div>
        </div>
    ";

    return $uvcontrolshtml;
}

/*Get Experiences Filters + List
    Optional: uvargs
    Args: "customclass": custom class on integration
    Returns: Prints html for experiences list
*/
function uws_experiences($uvargs = ""){
    global $uws_path, $uws_today;

    //$uvexperiences = uws_get_dummyapi("experiences");
    $uvdate = uws_get_arg($uvargs, "date", $uws_today);
    $uvexperiencesfilters = uws_get_experiences_filters($uvexperiences, $uvargs);
    $uvinvitems = uws_inventory_microcode_items(array("date" => $uvdate));

    //Add real items to dummy filters api --- shound connect the filters latter
    $uvexperiences["items"] = $uvinvitems;

    $uvexperienceslist = uws_get_experiences_list($uvexperiences);
    $uvintclass = uws_get_arg($uvargs, "customclass", "");

    $uvexperienceshtml = "
        <div class='uws-experiences $uvintclass uws-integration' data-date='$uvdate'>
            <div class='uws-experiences-filters'>
                $uvexperiencesfilters
            </div>
            <div class='uws-experiences-stage'>
                <div class='uws-experiences-list'>
                    $uvexperienceslist
                </div>
                <div class='uws-inventoryloader'>
                    <div class='uwsloadingmsg'>
                        <div class='uws-loader-uvicon'></div>
                        <div class='uwsloadingtxt'>Loading Experiences...</div>
                    </div>
                    <div class='uwsloadingbkt'></div>
                    <div class='uwsloadingbitem'></div>
                    <div class='uwsloadingbitem'></div>
                    <div class='uwsloadingbkt'></div>
                </div>
            </div>
        </div>
    ";

    echo $uvexperienceshtml;
}

/*Get Related Experiences
    Optional: uvargs
    Args: "customclass": custom class on integration
    Returns: Prints html for related experiences, skips the current item page
*/
function uws_related_experiences($uvargs = ""){
    $uvmastercode = uws_get_itempagemastercode();//current item page
    $uvexperiences = uws_get_dummyapi("experiences");
    $uvnexperiences = uws_get_arg($uvargs, "nexperiences", 3);
    $uvintclass = uws_get_arg($uvargs, "customclass", "");
    $uvexperlist = array("items" => array());

    if(is_array($uvexperiences)){
        foreach($uvexperiences["items"] as $uvthemastercode => $uvitem){
            if($uvthemastercode != $uvmastercode){
                $uvexperlist["items"][$uvthemastercode] = $uvitem;
                $uvnexperiences--;
            }

            if(!$uvnexperiences)//skip when max items is reached
                break;
        }
    }

    $uvexperienceslist = uws_get_experiences_list($uvexperlist);

    $uvexperienceshtml = "
        <div class='uws-related-experiences $uvintclass uws-integration uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='We do NOT have Related Experiences on the inventoryitem API, maybe we can get items from the same venue and date, or just the same date under the microcode, but we need to know the integration structure'>
            <div class='uws-experiences-list'>
                $uvexperienceslist
            </div>
        </div>
    ";

    echo $uvexperienceshtml;
}

/*Get Experiences List
    Requires: Experiences array
    Returns: Experiences List html
*/
function uws_get_experiences_list($uvexperiences){
    global $uws_core_lib;

    $uvexperienceslist = "";

    if(is_array($uvexperiences) and is_array($uvexperiences["items"])){
        $uvexperienceslistitemtemplate = uws_get_template("inventory/inventory-experience-list-item");

        foreach($uvexperiences["items"] as $uvitemmascode => $uvitem){
            $uvitemdprice = uws_frontformat_money($uvitem["listprice"], 1);
            $uvitemurl = uws_get_item_url($uvitem);
            $uvitimage = (isset($uvitem["image"])) ? $uvitem["image"] : "";
            $uvitdur = (isset($uvitem["activityduration"])) ? $uvitem["activityduration"] : "";
            $uvddate = date($uws_core_lib["inventory"]["global-dateformat"], strtotime($uvitem["caldate"]));

            $uvcapacitylabel = ($uvitem["capacity"] > 1) ? "Guests" : "Guest";
            //$uvdstartdtime = ($uvitem["startuvtime"]) ? uws_get_formattime($uvitem["startuvtime"]) : "";
            $uvdstartdtime = ($uvitem["starttime"]) ? uws_get_formattime($uvitem["starttime"]) : "";
            $uvdenddtime = ($uvitem["endtime"]) ? uws_get_formattime($uvitem["endtime"]) : "";
            $uvdtimerange = ($uvdstartdtime) ? $uvdstartdtime : "";
            $uvdtimerange = ($uvdstartdtime and $uvdenddtime) ? $uvdstartdtime . " - " . $uvdenddtime : $uvdtimerange;

            $uvitemhtml = str_replace(
                array(
                    "{mascode}",
                    "{itemname}",
                    "{frontprice}",
                    "{imgeimage}",
                    "{actduration}",
                    "{highlight}",
                    "{itemlink}",
                    "{mastercode}",
                    "{itemddate}",
                    "{itemcapacity}",
                    "{itemcapacitylabel}",
                    "{itemtimerange}",
                    "{itemdescription}"
                ),
                array(
                    $uvitemmascode,
                    $uvitem["itemname"],
                    $uvitemdprice,
                    $uvitimage,
                    $uvitdur,
                    $uvitem["highlight"],
                    $uvitemurl,
                    $uvitem["mastercode"],
                    $uvddate,
                    $uvitem["capacity"],
                    $uvcapacitylabel,
                    $uvdtimerange,
                    $uvitem["descr"]
                ),
                $uvexperienceslistitemtemplate
            );

            if(isset($uvitem["complimentary"]) and $uvitem["complimentary"])
                $uvitemhtml = str_replace(array("{ifcomplimentary}", "{/ifcomplimentary}"), array("", ""), $uvitemhtml);
            else
                $uvitemhtml = preg_replace('/{ifcomplimentary}(.|\n)*{\/ifcomplimentary}/', '', $uvitemhtml);

            $uvexperienceslist .= $uvitemhtml;
        }
    }

    return $uvexperienceslist;
}



/*Get Experiences Filters
    Requires: Experiences array
    Returns: Experiences filters html
*/
function uws_get_experiences_filters($uvexperiences, $uvargs = ""){
    $uvexperiencesfilters = "";

    if(is_array($uvexperiences)){
        $uvexpcats = $uvexperiences["activities"];
        $uvexpreco = $uvexperiences["recommentations"];
        $uvexpacty = $uvexperiences["activitytypes"];
        $uvexpbudg = $uvexperiences["budgets"];
        $uvexptoda = $uvexperiences["timeofday"];
        $uvexpcomp = $uvexperiences["complimentary"];

        $uvexpcatslist = uws_get_optionslist($uvexpcats);
        $uvexprecolist = uws_get_optionslist($uvexpreco, "", "checkboxes");
        $uvexpactylist = uws_get_optionslist($uvexpacty, "", "checkboxes");
        $uvexpbudglist = uws_get_optionslist($uvexpbudg, "", "checkboxes");
        $uvexptodalist = uws_get_optionslist($uvexptoda, "", "checkboxes");
        $uvexpcomplist = uws_get_optionslist($uvexpcomp, "", "checkboxes");

        $uvexperiencesfilter = uws_get_experiences_date_filter($uvargs);

        $uvexperiencesfilters = "
            <div class='uwsfilter uwsexpfilterdate'>
                $uvexperiencesfilter
            </div>
            <div class='uwsfilter uwsexpfiltercat uws-dropdown-cont uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='Items do not have category on the API, maybe we can use venuetype, but I need to see the structure that is going to be used on the integration'>
                <a href='#uws-opendateselection' class='uwsjs-trigger-dropdown' aria-label='Select Experiences Category'><span class='uwsdy-dropvalue'>All Activities</span></a>
                <div class='uws-dropdown'>
                    <ul class='uwslilist'>
                        <li class='uwscurrent'><a href='#uwsexpfilter-all' data-value='all'>All Activities</a></li>
                        $uvexpcatslist
                    </ul>
                </div>
            </div>
            <div class='uwsfilteraction uwsexpclearfilter'>
                <a href='#uws-exp-clearfilter' class='uws-btn uws-btn-s uws-btn-100'><span>Reset Filters</span></a>
            </div>
            <div class='uwsfilter uwsexpfilterreco uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='Recommendations are not in the API at all'>
                <div class='uwstitle'>Recommendations</div>
                <ul class='uwslilistchecks'>
                    $uvexprecolist
                </ul>
            </div>
            <div class='uwsfilter uwsexpfilteracty uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='Items do not have activity type on the API, maybe we can use venuetype or venue tags, but I need to see the structure that is going to be used on the integration'>
                <div class='uwstitle'>Activity Types</div>
                <ul class='uwslilistchecks'>
                    $uvexpactylist
                </ul>
            </div>
            <div class='uwsfilter uwsexpfilterbudg uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='API does not have price ranges, maybe we can calculate it but we would need to use the -pricelist- field'>
                <div class='uwstitle'>Budget</div>
                <ul class='uwslilistchecks'>
                    $uvexpbudglist
                </ul>
            </div>
            <div class='uwsfilter uwsexpfiltertoda uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='API does not have time of day, maybe we can use something on the venue configuration, we use something similar on crossbook api, but I do not see any field that could help on the inventorylist api'>
                <div class='uwstitle'>Time of Day</div>
                <ul class='uwslilistchecks'>
                    $uvexptodalist
                </ul>
            </div>
            <div class='uwsfilter uwsexpfiltercomp uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='There is nothing on the inventorylist API ralated to this'>
                <div class='uwstitle'>Complimentary</div>
                <ul class='uwslilistchecks'>
                    $uvexpcomplist
                </ul>
            </div>
        ";
    }

    return $uvexperiencesfilters;
}