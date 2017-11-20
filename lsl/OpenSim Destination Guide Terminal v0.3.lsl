// OpenSim Destination Guide Terminal v0.3 by djphil (CC-BY-NC-SA 4.0)

string  targetUrl      = "http://domaine.com/osguide/";
string  terminal_name  = "OpenSim Destination Guide Terminal";
string  categorie_name = "Official location";
float   update_rate    = 300.0;
integer display_debug  = FALSE;
integer display_guide  = TRUE;
integer display_text   = TRUE;
integer exclude_npc    = TRUE;
integer face           = ALL_SIDES;

// 1 Official location
// 3 Arts and culture
// 4 Business
// 5 Educationnal
// 6 Gaming
// 7 Hangout
// 8 Newcomer friendly
// 9 Parks and Nature
// 10 Residential
// 11 Shopping
// 13 Other
// 14 Rental

string server_uuid;
string region_name;
string owner_name;
string owner_uuid;
string script_name;

key http_request_id;
key tiny_request_id;
key serv_request_id;
key ping_request_id;

terminal_fullbright_flash()
{
    llSetLinkPrimitiveParamsFast(LINK_SET, [PRIM_FULLBRIGHT, face, TRUE]);
    llSleep(0.25);
    llSetLinkPrimitiveParamsFast(LINK_SET, [PRIM_FULLBRIGHT, face, FALSE]);
}

key addRegionToDestinationGuideID;
addRegionToDestinationGuide(string url)
{
    integer scope = AGENT_LIST_PARCEL_OWNER;
    if (exclude_npc) scope = AGENT_LIST_PARCEL_OWNER | AGENT_LIST_EXCLUDENPC;
    list agents_list = llGetAgentList(scope, []);
    integer agents_online = llGetListLength(agents_list);

    addRegionToDestinationGuideID = llHTTPRequest(targetUrl + "inc/terminal.php",
        [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded", HTTP_BODY_MAXLENGTH, 16384],
        "terminal=register" + 
        "&categorie_name=" + llEscapeURL(categorie_name) +
        "&http_server_url=" + llStringToBase64(http_server_url) + 
        // "&agents_online=" + agents_online +
        "&agents_list=" + llStringToBase64(llList2CSV(agents_list))
    );
}

string http_server_url;
request_http_server_url()
{
    llReleaseURL(http_server_url);
    http_server_url = "";
    llRequestURL();
}

verify_region_parcel_owner()
{
    list parcel_details   = llGetParcelDetails(llGetPos(), [PARCEL_DETAILS_NAME, PARCEL_DETAILS_OWNER]);
    string parcel_name    = llList2String(parcel_details, 0);
    key parcel_owner_uuid = llList2Key(parcel_details, 1);

    if (owner_uuid != parcel_owner_uuid)
    {
        llOwnerSay("\nDésolé " + owner_uuid + ", la parcelle " + parcel_name + " ne t'appartient pas ...");

        llInstantMessage(parcel_owner_uuid, 
            "[ALERT] " + osKey2Name(parcel_owner_uuid) + 
            "\nUn object nomé \"" + llGetObjectName() + "\"" + 
            " contenant un script nomé \"" + script_name + "\"" + 
            " à été rezzer par " + owner_name + 
            " sur la parcelle " + parcel_name + 
            " de la région " + region_name +
            " sur la grille " + osGetGridName() +
            "\n[OBJET UUID] " + llGetKey() +
            "\n[OWNER UUID] " + owner_uuid
        );

        llDie();
    }
}

default 
{
    state_entry()
    {
        llOwnerSay("Initialisation ...");

        owner_uuid  = llGetOwner();
        owner_name  = osKey2Name(owner_uuid);
        region_name = llGetRegionName();
        script_name = llGetScriptName();

        verify_region_parcel_owner();

        llSetObjectName(script_name);
        llSetObjectDesc("Digital Concepts (CC-BY-NC-SA 4.0)");

        if (display_text) llSetText("[✪ TERMINAL ✪]\n" + region_name + "\n(" + owner_name + ")", <1.0, 1.0, 1.0>, 1.0);
        else llSetText("", <1.0, 1.0, 1.0>, 1.0);

        llSetTexture(osGetMapTexture(), face);
        llSetTimerEvent(0.1);
    }

    touch_start(integer n)
    {
        key toucher_uuid = llDetectedKey(0);
        if (toucher_uuid == owner_uuid) llSetTimerEvent(0.1);
        else llRegionSayTo(toucher_uuid, PUBLIC_CHANNEL, "Onwer only " + osKey2Name(toucher_uuid) + " ...");
    }

    http_request(key id, string method, string body)
    {
        if (method == URL_REQUEST_GRANTED)
        {
            http_server_url = body;
            addRegionToDestinationGuide(http_server_url);
            tiny_request_id = llHTTPRequest("http://tinyurl.com/api-create.php?url=" + targetUrl, [], "");
            serv_request_id = llHTTPRequest("http://tinyurl.com/api-create.php?url=" + body, [], "");
            ping_request_id = llHTTPRequest(body, [], "");
        }
 
        else if (method == URL_REQUEST_DENIED)
        {
            llSay(PUBLIC_CHANNEL, "Something went wrong, no url. " + body);
        }

        else if (method == "GET")
        {
            llHTTPResponse(id, 200, "OpenSim Http Server for " + terminal_name + " is ready to use ...\n");
        }

        else if (method == "POST")
        {
            llHTTPResponse(id, 200, "OK");
            addRegionToDestinationGuide(http_server_url);
            terminal_fullbright_flash();
        }

        else {llHTTPResponse(id, 405, "Unsupported Method");  llOwnerSay("[ERROR] Unsupported Method ...");}
    }

    http_response(key id, integer status, list metadata, string body)
    {
        if (id)
        {
            if (status != 200)
            {
                llOwnerSay("[POST RECIEVED] " + status);
                return;
            }
        }

        else if (id == NULL_KEY)
        {
            llOwnerSay("[POST NULL & STATUS] " + status);
            return;
        }

        body = llStringTrim(body, STRING_TRIM);

        if (id == tiny_request_id && display_guide)
            llSay(PUBLIC_CHANNEL, "Guide @ " + body);
        if (id == serv_request_id && display_debug)
            llOwnerSay("Server @ " + body);
            
        if (id == addRegionToDestinationGuideID && display_debug)
        {
            string text  = "\n============================";
                   text += "\n" + body;
                   text += "\n============================";
            llOwnerSay(text);
        }

        if (id == ping_request_id && display_debug)
        {
            string text  = "\n============================";
                   text += "\n" + body;
                   text += "\n============================";
            llOwnerSay(text);  
        }

        if (body == "HOST_RESTRICTION")
        {
            body  = "\n* Votre \"host\" n'est pas autorisé à utiliser ce guide ...";
            body += "\n* La région \"" + region_name + "\" n'a pas été ajoutées ...";
            llOwnerSay("[WARNING] " + owner_name + body);
        }

        if (body == "UUID_RESTRICTION")
        {
            body  = "\n* La catégorie \"" + categorie_name + "\" est réservée ...";
            body += "\n* La région \"" + region_name + "\" n'a pas été ajoutées ...";
            llOwnerSay("[WARNING] " + owner_name + body);
        }
    }

    timer()
    {
        llSetTimerEvent(update_rate);
        request_http_server_url();
        terminal_fullbright_flash();
    }

    on_rez(integer n)
    {
        llSetTimerEvent(0.0);
        verify_region_parcel_owner();
    }

    changed(integer change)
    {
        if (change & CHANGED_INVENTORY)    {llResetScript();}
        if (change & CHANGED_OWNER)        {llResetScript();}
        if (change & CHANGED_REGION)       {llResetScript();}
        if (change & CHANGED_REGION_START) {llResetScript();}
    }
}