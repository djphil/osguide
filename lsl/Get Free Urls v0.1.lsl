// Get Free Urls v0.1 by djphil (CC_BY-NC-SA 4.0)

default
{
    state_entry()
    {
        llOwnerSay("Free URLs left " + (string)llGetFreeURLs());
    }

    touch_start(integer number)
    {
        llOwnerSay("Free URLs left " + (string)llGetFreeURLs());
    }
}