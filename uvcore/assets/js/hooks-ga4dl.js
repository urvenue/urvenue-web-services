var uvga_curitemlistid = "";
var uvga_curitemlistname = "";
window.dataLayer = window.dataLayer || [];

function uvhookInventoryListLoaded(uvinvlistinfo){
    if(typeof(uvinvlistinfo) == "object" && typeof(uvinvlistinfo.items) == "object"){
        let uvlistname = uvlistid = uvlistvenuecode = "";

        if(typeof(uvinvlistinfo.eventdata) == "object"){
            uvlistname = (uvinvlistinfo.eventdata.name) ? `${uvinvlistinfo.eventdata.name} : ${uvinvlistinfo.eventdata.venuename} : ${uvinvlistinfo.eventdata.date}` : `Inventory List For: ${uvinvlistinfo.eventdata.eventcode}`;
            uvlistid = "event_page_" + uvinvlistinfo.eventdata.eventcode;
            uvlistvenuecode = uvinvlistinfo.eventdata.venuecode;
        }
        else{
            uvlistname = uvga_curitemlistname;
            uvlistid = uvga_curitemlistid;
        }

        let uvecoitems = [];

        uvga_curitemlistid = (uvlistid) ? uvlistid : uvga_curitemlistid;
        uvga_curitemlistname = (uvlistname) ? uvlistname : uvga_curitemlistname;

        Object.entries(uvinvlistinfo.items).forEach(([uvmascode, uvitem]) => {
            const uvecoitem = {
                item_id: uvitem.mastercode,
                item_name: uvitem.itemname,
                item_brand: "UrVenue Inventory",
                item_category: uvitem.globaltype,
                item_category2: uvitem.booktypename,
                item_list_id: uvlistid,
                item_list_name: uvlistname,
                price: uvitem.listprice,
                quantity: uvitem.minqty,
                guests: uvitem.capacity,
                venuecode: uvitem.venuecode,
            }
            uvecoitems.push(uvecoitem);

            uvlistvenuecode = (!uvlistvenuecode && uvitem.venuecode) ? uvitem.venuecode : uvlistvenuecode;
        });

        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "view_item_list",
            venuecode: uvlistvenuecode,
            ecommerce: {
                item_list_id: uvlistid,
                item_list_name: uvlistname,
                items: uvecoitems,
            },
        });
    }
}

function uvhookInvItemAdded(uviteminfo, uvitemsels){
    if(typeof(uviteminfo) == "object" && typeof(uviteminfo.info) == "object" && typeof(uvitemsels) == "object"){
        const uvitemguests = (uviteminfo.info.qtytype == "guests") ? uvitemsels.guests : uvitemsels.guests * uviteminfo.info.capacity;
        const uvitemqty = (uviteminfo.info.qtytype == "guests") ? uviteminfo.info.minqty : uvitemsels.guests;

        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "add_to_cart",
            venuecode: uviteminfo.info.venuecode,
            ecommerce: {
                currency: "USD",
                value: uvitemsels.selprice,
                items: [{
                    item_id: uviteminfo.info.mastercode,
                    item_name: uviteminfo.info.itemname,
                    item_brand: "UrVenue Inventory",
                    item_category: uviteminfo.info.globaltype,
                    price: uvitemsels.selprice,
                    quantity: uvitemqty,
                    guests: uvitemguests,
                    venuecode: uviteminfo.info.venuecode,
                }]
            }
        });

        if(typeof(fbq) == "function")
            fbq('track', 'AddToCart');
    }
}

function uvhookItemRemoved(uvresponse){
    if(typeof(uvresponse) == "object" && typeof(uvresponse.item) == "object" && typeof(uvresponse.item.info) == "object"){
        const uvitem = uvresponse.item;

        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "remove_from_cart",
            venuecode: uvitem.info.venuecode,
            ecommerce: {
                currency: "USD",
                value: uvitem.info.listprice,
                items: [{
                    item_id: uvitem.info.mastercode,
                    item_name: uvitem.info.itemname,
                    item_brand: "UrVenue Inventory",
                    item_category: uvitem.info.globaltype,
                    price: uvitem.info.listprice,
                    quantity: uvitem.info.minqty,
                    guests: uvitem.info.capacity,
                    venuecode: uvitem.info.venuecode,
                }]
            }
        });
    }
}

function uvhookItemPopOpened(uviteminfo){
    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "select_item",
        venuecode: uviteminfo.info.venuecode,
        ecommerce: {
            item_list_id: uvga_curitemlistid,
            item_list_name: uvga_curitemlistname,
            items: [{
                item_id: uviteminfo.info.mastercode,
                item_name: uviteminfo.info.itemname,
                item_brand: "UrVenue Inventory",
                item_category: uviteminfo.info.globaltype,
                item_list_id: uvga_curitemlistid,
                item_list_name: uvga_curitemlistname,
                price: uviteminfo.info.listprice,
                quantity: uviteminfo.info.minqty,
                guests: uviteminfo.info.capacity,
                venuecode: uviteminfo.info.venuecode,
            }]
        }
    });
}

function uvhookMapLoaded(uvmaploadinfo){
    if(typeof(uvmaploadinfo) == "object" && typeof(uvmaploadinfo.items) == "object"){
        const uvlistname = `Map Inventory List For: ${uvmaploadinfo.eventcode}`;
        const uvlistid = "map_page_" + uvmaploadinfo.eventcode;
        let uvecoitems = [];

        uvga_curitemlistid = (uvlistid) ? uvlistid : uvga_curitemlistid;
        uvga_curitemlistname = (uvlistname) ? uvlistname : uvga_curitemlistname;

        if(typeof(uvmaploadinfo.eventdata) == "object"){
            Object.entries(uvmaploadinfo.items).forEach(([uvmascode, uvitem]) => {
                const uvecoitem = {
                    item_id: uvitem.mastercode,
                    item_name: uvitem.itemname,
                    item_brand: "UrVenue Inventory",
                    item_category: uvitem.globaltype,
                    item_category2: uvitem.booktypename,
                    item_list_id: uvlistid,
                    item_list_name: uvlistname,
                    price: uvitem.listprice,
                    quantity: uvitem.minqty,
                    guests: uvitem.capacity,
                    venuecode: uvitem.venuecode,
                }
                uvecoitems.push(uvecoitem);
            });

            dataLayer.push({ ecommerce: null });
            dataLayer.push({
                event: "view_item_list",
                ecommerce: {
                    item_list_id: uvlistid,
                    item_list_name: uvlistname,
                    items: uvecoitems,
                },
            });
        }
    }
}