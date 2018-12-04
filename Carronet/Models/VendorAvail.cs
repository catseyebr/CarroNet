using System.Collections.Generic;

namespace Carronet.Models
{
    public class VendorAvail
    {
        string codeXml;
        string pickupLocation;
        string returnLocation;
        int idPickupLocation;
        int idReturnLocation;
        bool aeroReti;
        bool aeroDevo;
        string ratePeriod;
        string rateCategory;
        string airConditionInd;
        string transmissionType;
        string description;
        string baggageQuantity;
        string passengerQuantity;
        string code;
        string vehicleCategory;
        string doorCount;
        string size;
        string modelo;
        string rateDistance_Unlimited;
        string rateDistance_DistUnitName;
        string rateDistance_VehiclePeriodUnitName;
        string rateDistance_Quantity;
        Dictionary<string, VehicleCharges> vehicleCharges;
        string rate_RatePeriod;
        string rate_RateQualifier;
        string rate_RateCategory;
        string minimumDayInd;
        string maximumDayInd;
        string totalCharge_RateTotalAmount;
        float totalCharge_EstimatedTotalAmount;
        string totalCharge_CurrencyCode;
        string totalCharge_DecimalPlaces;
        string totalCharge_RateConvertInd;
        Dictionary<string,Fee> fees;
        string references_ID;
        string references_Type;
        Dictionary<string,Coverage> coverage;

        public string CodeXml { get => codeXml; set => codeXml = value; }
        public string PickupLocation { get => pickupLocation; set => pickupLocation = value; }
        public string ReturnLocation { get => returnLocation; set => returnLocation = value; }
        public int IdPickupLocation { get => idPickupLocation; set => idPickupLocation = value; }
        public int IdReturnLocation { get => idReturnLocation; set => idReturnLocation = value; }
        public bool AeroReti { get => aeroReti; set => aeroReti = value; }
        public bool AeroDevo { get => aeroDevo; set => aeroDevo = value; }
        public string RatePeriod { get => ratePeriod; set => ratePeriod = value; }
        public string RateCategory { get => rateCategory; set => rateCategory = value; }
        public string AirConditionInd { get => airConditionInd; set => airConditionInd = value; }
        public string TransmissionType { get => transmissionType; set => transmissionType = value; }
        public string Description { get => description; set => description = value; }
        public string BaggageQuantity { get => baggageQuantity; set => baggageQuantity = value; }
        public string PassengerQuantity { get => passengerQuantity; set => passengerQuantity = value; }
        public string Code { get => code; set => code = value; }
        public string VehicleCategory { get => vehicleCategory; set => vehicleCategory = value; }
        public string DoorCount { get => doorCount; set => doorCount = value; }
        public string Size { get => size; set => size = value; }
        public string Modelo { get => modelo; set => modelo = value; }
        public string RateDistance_Unlimited { get => rateDistance_Unlimited; set => rateDistance_Unlimited = value; }
        public string RateDistance_DistUnitName { get => rateDistance_DistUnitName; set => rateDistance_DistUnitName = value; }
        public string RateDistance_VehiclePeriodUnitName { get => rateDistance_VehiclePeriodUnitName; set => rateDistance_VehiclePeriodUnitName = value; }
        public string RateDistance_Quantity { get => rateDistance_Quantity; set => rateDistance_Quantity = value; }
        public Dictionary<string, VehicleCharges> VehicleCharges { get => vehicleCharges; set => vehicleCharges = value; }
        public string Rate_RatePeriod { get => rate_RatePeriod; set => rate_RatePeriod = value; }
        public string Rate_RateQualifier { get => rate_RateQualifier; set => rate_RateQualifier = value; }
        public string Rate_RateCategory { get => rate_RateCategory; set => rate_RateCategory = value; }
        public string MinimumDayInd { get => minimumDayInd; set => minimumDayInd = value; }
        public string MaximumDayInd { get => maximumDayInd; set => maximumDayInd = value; }
        public string TotalCharge_RateTotalAmount { get => totalCharge_RateTotalAmount; set => totalCharge_RateTotalAmount = value; }
        public float TotalCharge_EstimatedTotalAmount { get => totalCharge_EstimatedTotalAmount; set => totalCharge_EstimatedTotalAmount = value; }
        public string TotalCharge_CurrencyCode { get => totalCharge_CurrencyCode; set => totalCharge_CurrencyCode = value; }
        public string TotalCharge_DecimalPlaces { get => totalCharge_DecimalPlaces; set => totalCharge_DecimalPlaces = value; }
        public string TotalCharge_RateConvertInd { get => totalCharge_RateConvertInd; set => totalCharge_RateConvertInd = value; }
        public Dictionary<string,Fee> Fees { get => fees; set => fees = value; }
        public string References_ID { get => references_ID; set => references_ID = value; }
        public string References_Type { get => references_Type; set => references_Type = value; }
        public Dictionary<string,Coverage> Coverage { get => coverage; set => coverage = value; }
    }
}