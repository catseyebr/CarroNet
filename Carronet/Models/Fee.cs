namespace Carronet.Models
{
    public class Fee
    {
        string includedInRate;
        string rateConvertInd;
        string includedInEstTotalInd;
        string description;
        string decimalPlaces;
        string currencyCode;
        string taxInclusive;
        string amount;
        string purpose;
        string percentage;
        string total;

        public string IncludedInRate { get => includedInRate; set => includedInRate = value; }
        public string RateConvertInd { get => rateConvertInd; set => rateConvertInd = value; }
        public string IncludedInEstTotalInd { get => includedInEstTotalInd; set => includedInEstTotalInd = value; }
        public string Description { get => description; set => description = value; }
        public string DecimalPlaces { get => decimalPlaces; set => decimalPlaces = value; }
        public string CurrencyCode { get => currencyCode; set => currencyCode = value; }
        public string TaxInclusive { get => taxInclusive; set => taxInclusive = value; }
        public string Amount { get => amount; set => amount = value; }
        public string Purpose { get => purpose; set => purpose = value; }
        public string Percentage { get => percentage; set => percentage = value; }
        public string Total { get => total; set => total = value; }
    }
}