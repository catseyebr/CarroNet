using System.Collections.Generic;

namespace Carronet.Models
{
    public class TarifaOnlineWs
    {
        int locadora;
	    string dataRetirada;
        string dataDevolucao;
	    string promo_codigo;
        string[] lojasRetiArr;
	    string[] lojasDevoArr;
        Dictionary<string, int> lojasReti;
        Dictionary<string, int> lojasDevo;
        Dictionary<string, bool> airportReti;
        Dictionary<string, bool> airportDevo;
	    
        public int Locadora { get => locadora; set => locadora = value; }
        public string DataRetirada { get => dataRetirada; set => dataRetirada = value; }
        public string DataDevolucao { get => dataDevolucao; set => dataDevolucao = value; }
        public string Promo_codigo { get => promo_codigo; set => promo_codigo = value; }
        public string[] LojasRetiArr { get => lojasRetiArr; set => lojasRetiArr = value; }
        public string[] LojasDevoArr { get => lojasDevoArr; set => lojasDevoArr = value; }
        public Dictionary<string, int> LojasReti { get => lojasReti; set => lojasReti = value; }
        public Dictionary<string, int> LojasDevo { get => lojasDevo; set => lojasDevo = value; }
        public Dictionary<string, bool> AirportReti { get => airportReti; set => airportReti = value; }
        public Dictionary<string, bool> AirportDevo { get => airportDevo; set => airportDevo = value; }
    }
}