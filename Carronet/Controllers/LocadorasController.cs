using Carronet.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Web;
using System.Web.Mvc;
using System.Web.UI;

namespace Carronet.Controllers
{
    public class LocadorasController : Controller
    {
        // GET: Locadoras
        public void Index()
        {
            AvailThread result = new AvailThread();
            result.Start();
            Response.Write("Feito");
            //return locadoraResponse.PickupDateTime;
        }
    }
}