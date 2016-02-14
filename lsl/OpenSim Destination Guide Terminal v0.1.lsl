// OpenSim Destination Guide Terminal v0.1 by djphil (CC-BY-NC-SA 4.0)

string  targetUrl      = "http://domaine.com/osguide/inc/";
string  terminal_name  = "OpenSim Destination Guide Terminal";
string  categorie_name = "Official location";
float   update_rate    = 300.0;
integer display_debug  = FALSE;

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

key http_request_id;
key tiny_request_id;
key serv_request_id;
key ping_request_id;

terminal_fullbright_flash()
{
    llSetLinkPrimitiveParamsFast(LINK_SET, [PRIM_FULLBRIGHT, ALL_SIDES, TRUE]);
    llSleep(0.25);
    llSetLinkPrimitiveParamsFast(LINK_SET, [PRIM_FULLBRIGHT, ALL_SIDES, FALSE]);
}

key addRegionToDestinationGuideID;
addRegionToDestinationGuide(string url)
{
    list agents_list = llGetAgentList(AGENT_LIST_PARCEL_OWNER, []);
    integer agents_online = llGetListLength(agents_list);

    addRegionToDestinationGuideID = llHTTPRequest(targetUrl + "terminal.php",
        [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded", HTTP_BODY_MAXLENGTH, 16384],
        "terminal=register" + 
        "&categorie_name=" + llEscapeURL(categorie_name) +
        "&http_server_url=" + llStringToBase64(http_server_url) + 
        // "&agents_online=" + agents_online +
        "&agents_list=" + llStringToBase64(llList2CSV(agents_list))
    );
}

getObjectDetails()
{
    list buffer = llGetObjectDetails(llDetectedKey(0), ([
        OBJECT_NAME,
        OBJECT_DESC, 
        OBJECT_POS, 
        OBJECT_ROT, 
        OBJECT_VELOCITY,
        OBJECT_OWNER, 
        OBJECT_GROUP, 
        OBJECT_CREATOR
    ]));
    
    string text;
    text += "UUID: "            + (string)llDetectedKey(0);
    text += "\nNom: \""         + llList2String(buffer, 0) + "\"";
    text += "\nDescription: \"" + llList2String(buffer, 1) + "\"";
    text += "\nPosition: "      + llList2String(buffer, 2);
    text += "\nRotation: "      + llList2String(buffer, 3);
    text += "\nVitesse: "       + llList2String(buffer, 4);
    text += "\nPropriétaire: "  + llList2String(buffer, 5);
    text += "\nGroupe: "        + llList2String(buffer, 6);
    text += "\nCreateur: "      + llList2String(buffer, 7);
    llOwnerSay(text);
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
    string parcel_name    =  llList2String(parcel_details, 0);
    key parcel_owner_uuid = llList2String(parcel_details, 1);
    key object_owner_uuid = llGetOwner();

    if (object_owner_uuid == parcel_owner_uuid)
    {
        llResetScript();
    }

    else
    {
        llOwnerSay("\nDésolé " + osKey2Name(object_owner_uuid) + " la parcelle " + parcel_name + " ne t'appartient pas ...");

        llInstantMessage(parcel_owner_uuid, 
            "Bonjour " + osKey2Name(parcel_owner_uuid) + 
            "\nUn object nomé \"" + llGetObjectName() + "\"" + 
            " à été rezzer par " + llKey2Name(object_owner_uuid) + 
            " sur la parcelle " + parcel_name + 
            " de la région " + llGetRegionName() +
            " sur la grille " + osGetGridName() +
            "\n[OBJET UUID] " + llGetKey() +
            "\n[OWNER UUID] " + object_owner_uuid
        );

        llDie();
    }   
}

default 
{
    state_entry()
    {
        llOwnerSay("Initialisation ...");
        verify_region_parcel_owner();

        region_name = llGetRegionName();
        owner_uuid  = llGetOwner();
        owner_name  = osKey2Name(owner_uuid);

        llSetObjectName(llGetScriptName());
        llSetObjectDesc("Digital Concepts (CC-BY-NC-SA 4.0)");
        llSetText("[? TERMINAL ?]\n" + region_name + "\n(" + owner_name + ")", <1.0, 1.0, 1.0>, 1.0);
        llSetTexture(osGetMapTexture(),ALL_SIDES); // TL None
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

        if (id == tiny_request_id)
            llSay(PUBLIC_CHANNEL, "Guide @ " + body);
        if (id == serv_request_id)
            llSay(PUBLIC_CHANNEL, "Server @ " + body);
            
        if (id == addRegionToDestinationGuideID && display_debug == TRUE)
        {
            string text  = "\n============================";
                   text += "\n" + llStringTrim(body, STRING_TRIM);
                   text += "\n============================";
            llOwnerSay(text);
        }

        if (id == ping_request_id && display_debug == TRUE)
        {
            string text  = "\n============================";
                   text += "\n" + llStringTrim(body, STRING_TRIM);
                   text += "\n============================";
            llOwnerSay(text);  
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