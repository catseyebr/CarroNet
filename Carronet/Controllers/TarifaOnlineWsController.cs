using Carronet.Models;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Web.Http;

namespace Carronet.Controllers
{

    public class TarifaOnlineWsController : ApiController
    {
        public async Task<LocadoraResponse> GenerateSearch([FromBody]TarifaOnlineWs tarifaOnline)
        {
            AvailThread resultado = new AvailThread(tarifaOnline);
            await resultado.Start();
            
            var j = this.GenResponse(resultado.AvailList);
            return j;
        }       

        private LocadoraResponse GenResponse(List<LocadoraResponse> avails)
        {
            LocadoraResponse resp = new LocadoraResponse();
            Dictionary<string,VendorAvail> listAvail = new Dictionary<string,VendorAvail>();
            foreach (LocadoraResponse respLoc in avails){
                if(respLoc.VendorAvail != null) {
                    resp.PickupDateTime = respLoc.PickupDateTime;
                    resp.ReturnDatetime = respLoc.ReturnDatetime;
                    foreach (KeyValuePair<string, VendorAvail>  avail in respLoc.VendorAvail)
                    {
                        
                        if (!listAvail.ContainsKey(avail.Key))
                        {
                            listAvail.Add(avail.Key, avail.Value);
                        }
                        else
                        {
                            if (avail.Value.TotalCharge_EstimatedTotalAmount <= listAvail[avail.Key].TotalCharge_EstimatedTotalAmount)
                            {
                                if (!listAvail[avail.Key].AeroReti)
                                {
                                    listAvail[avail.Key] = avail.Value;
                                }
                                else
                                {
                                    if(avail.Value.TotalCharge_EstimatedTotalAmount < listAvail[avail.Key].TotalCharge_EstimatedTotalAmount)
                                    {
                                        listAvail[avail.Key] = avail.Value;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            resp.VendorAvail = listAvail;

            return resp;
        }

    }
}
