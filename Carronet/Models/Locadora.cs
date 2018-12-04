namespace Carronet.Models
{
    public class Locadora
    {
        int id;
        string xmlVar;
        string xmlRdCar;
        string nome;

        public int Id { get => id; set => id = value; }
        public string XmlVar { get => xmlVar; set => xmlVar = value; }
        public string XmlRdCar { get => xmlRdCar; set => xmlRdCar = value; }
        public string Nome { get => nome; set => nome = value; }

        public Locadora(){}
        public Locadora(int idLoc){
            Id = idLoc;
            this.SetLocadora();
        }

        public void SetLocadora()
        {
            switch (Id)
            {
                case 3:
                    XmlVar = "unidas";
                    XmlRdCar = "";
                    Nome = "Unidas Rent a Car";
                    break;
                case 5:
                    XmlVar = "yes";
                    XmlRdCar = "";
                    Nome = "Yes Rent a Car";
                    break;
                case 15:
                    XmlVar = "movida";
                    XmlRdCar = "";
                    Nome = "Movida Rent a Car";
                    break;
                case 95:
                    XmlVar = "rdcars";
                    XmlRdCar = "3";
                    Nome = "Inova Rent a Car";
                    break;
                case 119:
                    XmlVar = "budget";
                    XmlRdCar = "";
                    Nome = "Budget Rent a Car";
                    break;
                case 124:
                    XmlVar = "avis";
                    XmlRdCar = "";
                    Nome = "Avis Rent a Car";
                    break;
                case 262:
                    XmlVar = "rdcars";
                    XmlRdCar = "2";
                    Nome = "Referência Rent a Car";
                    break;
                case 286:
                    XmlVar = "fleetmax";
                    XmlRdCar = "226";
                    Nome = "Rental Line Locadora";
                    break;
                case 288:
                    XmlVar = "rdcars";
                    XmlRdCar = "226";
                    Nome = "Mister Car Rent a Car";
                    break;
                case 446:
                    XmlVar = "rdcars";
                    XmlRdCar = "116";
                    Nome = "Mister Car Rent a Car";
                    break;
                case 517:
                    XmlVar = "rdcars";
                    XmlRdCar = "296";
                    Nome = "BM Rent a Car";
                    break;
                case 527:
                    XmlVar = "rdcars";
                    XmlRdCar = "526";
                    Nome = "Canoense Rent a Car";
                    break;
                case 594:
                    XmlVar = "rdcars";
                    XmlRdCar = "490";
                    Nome = "Karper Rent a Car";
                    break;
                case 693:
                    XmlVar = "foco";
                    XmlRdCar = "";
                    Nome = "Foco Rent a Car";
                    break;
                case 768:
                    XmlVar = "rdcars";
                    XmlRdCar = "534";
                    Nome = "LTR Rent a Car";
                    break;
                case 1323:
                    XmlVar = "localiza";
                    XmlRdCar = "";
                    Nome = "Localiza Rent a Car";
                    break;
                case 1410:
                    XmlVar = "maggi";
                    XmlRdCar = "";
                    Nome = "Maggi Rent a Car";
                    break;
                case 1681:
                    XmlVar = "rdcars";
                    XmlRdCar = "514";
                    Nome = "Maxirent Locadora de Veículos";
                    break;
                case 1752:
                    XmlVar = "alamo";
                    XmlRdCar = "";
                    Nome = "Alamo Rent a Car";
                    break;
                case 1773:
                    XmlVar = "rdcars";
                    XmlRdCar = "509";
                    Nome = "Memphis Rent a Car";
                    break;
                case 1882:
                    XmlVar = "rdcars";
                    XmlRdCar = "517";
                    Nome = "RHP Rent a Car";
                    break;
                case 2264:
                    XmlVar = "rdcars";
                    XmlRdCar = "504";
                    Nome = "Alternativacar Locadora";
                    break;
                case 2319:
                    XmlVar = "rdcars";
                    XmlRdCar = "529";
                    Nome = "Carro Reserva";
                    break;
                case 2340:
                    XmlVar = "rdcars";
                    XmlRdCar = "505";
                    Nome = "Pole Locadora";
                    break;
                case 2347:
                    XmlVar = "rdcars";
                    XmlRdCar = "531";
                    Nome = "Opportunity Rent a Car";
                    break;
                case 2376:
                    XmlVar = "rdcars";
                    XmlRdCar = "539";
                    Nome = "Berlim Aluguel de Carros";
                    break;
                case 2382:
                    XmlVar = "hertzbr";
                    XmlRdCar = "";
                    Nome = "Hertz Rent a Car";
                    break;
                case 2390:
                    XmlVar = "nacional";
                    XmlRdCar = "";
                    Nome = "National Rent a Car";
                    break;
                case 2397:
                    XmlVar = "rdcars";
                    XmlRdCar = "542";
                    Nome = "Alles Rent a Car";
                    break;
            }
        }
    }
}