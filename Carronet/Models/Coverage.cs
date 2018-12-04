using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace Carronet.Models
{
    public class Coverage
    {
        string coverageType;
        string details;
        string unitCharge;
        string quantity;
        string total;
        string included;

        public string CoverageType { get => coverageType; set => coverageType = value; }
        public string Details { get => details; set => details = value; }
        public string UnitCharge { get => unitCharge; set => unitCharge = value; }
        public string Quantity { get => quantity; set => quantity = value; }
        public string Total { get => total; set => total = value; }
        public string Included { get => included; set => included = value; }
    }
}