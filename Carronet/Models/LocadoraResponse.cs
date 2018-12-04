using System.Collections.Generic;

namespace Carronet.Models
{
    public class LocadoraResponse
    {
        string pickupDateTime;
        string returnDatetime;
        Dictionary<string,VendorAvail> vendorAvail;

        public string PickupDateTime { get => pickupDateTime; set => pickupDateTime = value; }
        public string ReturnDatetime { get => returnDatetime; set => returnDatetime = value; }
        public Dictionary<string,VendorAvail> VendorAvail { get => vendorAvail; set => vendorAvail = value; }
    }
}