using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace Carronet.Models
{
    public class VehicleCharges
    {
        string vehicleCharge_IncludedInRate;
        string vehicleCharge_RateConvertInd;
        string vehicleCharge_IncludedInEstTotalInd;
        string vehicleCharge_Description;
        string vehicleCharge_DecimalPlaces;
        string vehicleCharge_CurrencyCode;
        string vehicleCharge_TaxInclusive;
        string vehicleCharge_Amount;
        string vehicleCharge_Purpose;
        string calculation_UnitCharge;
        string calculation_UnitName;
        string calculation_Quantity;
        string calculation_Total;

        public string VehicleCharge_IncludedInRate { get => vehicleCharge_IncludedInRate; set => vehicleCharge_IncludedInRate = value; }
        public string VehicleCharge_RateConvertInd { get => vehicleCharge_RateConvertInd; set => vehicleCharge_RateConvertInd = value; }
        public string VehicleCharge_IncludedInEstTotalInd { get => vehicleCharge_IncludedInEstTotalInd; set => vehicleCharge_IncludedInEstTotalInd = value; }
        public string VehicleCharge_Description { get => vehicleCharge_Description; set => vehicleCharge_Description = value; }
        public string VehicleCharge_DecimalPlaces { get => vehicleCharge_DecimalPlaces; set => vehicleCharge_DecimalPlaces = value; }
        public string VehicleCharge_CurrencyCode { get => vehicleCharge_CurrencyCode; set => vehicleCharge_CurrencyCode = value; }
        public string VehicleCharge_TaxInclusive { get => vehicleCharge_TaxInclusive; set => vehicleCharge_TaxInclusive = value; }
        public string VehicleCharge_Amount { get => vehicleCharge_Amount; set => vehicleCharge_Amount = value; }
        public string VehicleCharge_Purpose { get => vehicleCharge_Purpose; set => vehicleCharge_Purpose = value; }
        public string Calculation_UnitCharge { get => calculation_UnitCharge; set => calculation_UnitCharge = value; }
        public string Calculation_UnitName { get => calculation_UnitName; set => calculation_UnitName = value; }
        public string Calculation_Quantity { get => calculation_Quantity; set => calculation_Quantity = value; }
        public string Calculation_Total { get => calculation_Total; set => calculation_Total = value; }
    }
}