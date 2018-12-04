using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Threading;
using System.Threading.Tasks;

namespace Carronet.Models
{
    public class AvailThread
    {
        CancellationTokenSource cts;
        private List<LocadoraResponse> availList = new List<LocadoraResponse>();
        private string baseUrl = "http://www.carroaluguel.net/locadoras/consultar.php";
        private string uri;
        private TarifaOnlineWs tarifaonline;
        private Locadora locadora;        
        
        public List<LocadoraResponse> AvailList { get => availList; set => availList = value; }
        public Locadora Locadora { get => locadora; set => locadora = value; }
        public TarifaOnlineWs Tarifaonline { get => tarifaonline; set => tarifaonline = value; }
        public string Uri { get => uri; set => uri = value; }
        public string BaseUrl { get => baseUrl; set => baseUrl = value; }

        public AvailThread() { }

        public AvailThread(TarifaOnlineWs tarOnline)
        {
            Tarifaonline = tarOnline;
            Locadora = new Locadora(Tarifaonline.Locadora);
            Uri = "?loc=" + Locadora.XmlVar + "&rdcar=" + Locadora.XmlRdCar + "&dtreti=" + Tarifaonline.DataRetirada + "&dtdevo=" + Tarifaonline.DataDevolucao + "&promo=" + Tarifaonline.Promo_codigo;
        }

        public async Task Start()
        {
            cts = new CancellationTokenSource();
               await AccessTheWebAsync(cts.Token);
            cts = null;
        }

        
        async Task AccessTheWebAsync(CancellationToken ct)
        {
            HttpClient client = new HttpClient();
            List<string> urlList = SetUpURLList();
            IEnumerable<Task<LocadoraResponse>> downloadTasksQuery =
                from url in urlList select ProcessURL(url, client, ct);
            List<Task<LocadoraResponse>> downloadTasks = downloadTasksQuery.ToList();
            while (downloadTasks.Count > 0)
            {
                Task<LocadoraResponse> firstFinishedTask = await Task.WhenAny(downloadTasks);
                downloadTasks.Remove(firstFinishedTask);
                LocadoraResponse length = await firstFinishedTask;
            }
        }

        private List<string> SetUpURLList()
        {
            List<string> urls = new List<string>();
            foreach (string iata in Tarifaonline.LojasRetiArr)
            {
                bool hasDevo = Array.IndexOf(Tarifaonline.LojasDevoArr, iata) >= 0;
                string devo;
                if (hasDevo) {
                    devo = iata;
                }
                else
                {
                    devo = Tarifaonline.LojasDevoArr.First();
                }
                string loja = baseUrl + Uri + "&iatareti=" + iata + "&iatadevo=" + devo + "&reti=" + Tarifaonline.LojasReti[iata] + "&devo=" + Tarifaonline.LojasDevo[devo] + "&aeroreti=" + Tarifaonline.AirportReti[iata] + "&aerodevo=" + Tarifaonline.AirportDevo[devo];
                urls.Add(loja);
            }            
            return urls;
        }

        async Task<LocadoraResponse> ProcessURL(string url, HttpClient client, CancellationToken ct)
        {
            new MediaTypeWithQualityHeaderValue("application/json");
            HttpResponseMessage response = await client.GetAsync(url, ct);
            LocadoraResponse urlContents = response.Content.ReadAsAsync<LocadoraResponse>().Result;

            this.AvailList.Add(urlContents);
            return urlContents;
        }        
    }
}