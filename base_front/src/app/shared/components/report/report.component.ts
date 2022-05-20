import { Component, OnInit, Input } from '@angular/core';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

import { Chart } from 'frappe-charts';
import { BsLocaleService } from 'ngx-bootstrap/datepicker';
import { listLocales } from 'ngx-bootstrap/chronos';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import * as moment from 'moment';
import 'moment/locale/es';

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.scss'],
})
export class ReportComponent implements OnInit {

  opened = false;

  frequencies = [
    {
        id : 1 ,
        name : '1 h'
    },
    {
        id : 3 ,
        name : '3 h'
    },
    {
        id : 12 ,
        name : '12 h'
    },
    {
        id : 24 ,
        name : '24 h'
    },
  ] 
  chartIca;
  chartTemperature;
  chartHumidity;
  chartPressure;
  chartElevation;

  @Input() companies;
  @Input() selectCompany;
  @Input() selectDevice;
  @Input() 
  set companyId(companyId: string) 
  {
    this.form.setValue({
        company_id : companyId ,
        range_date : '',
        device_id : '',
        frequency : ''
    });
    this.getDevice(companyId);
  }
  @Input() 
  set _devices(devices) 
  {
      this.devices = devices;
  }
  @Input() 
  set deviceId(deviceId: string) 
  {    
    this.form.setValue({
        company_id : '',
        range_date : '',
        device_id : deviceId ,
        frequency : ''
    });
  }

  showDetail = false;

  showChartIca = false;
  showChartTemperature = false;
  showChartHumidity = false;
  showChartPressure = false;
  showChartElevation = false;

  reportIca = [];
  reportTemperature = [];
  reportHumidity = [];
  reportPressure  = [];
  reportElevation  = [];

  locale = 'es';
  locales = listLocales();

  devices = [];

  form!: FormGroup;

  device:any;

  constructor(
    public service: ApirestService,
    public singleton: SingletonService,
    private localeService: BsLocaleService,
    private formBuilder: FormBuilder
  ) {}

  ngOnInit(): void {
    this.applyLocale();
    this.buildForm();
  }

  private buildForm() {
    let validatorsCompanies = [];
    let validatorsDevices = [];

    if (this.selectCompany == true) {
      validatorsCompanies.push(Validators.required);
    }

    if (this.selectDevice == true) {
      validatorsDevices.push(Validators.required);
    }

    this.form = this.formBuilder.group({
      company_id: ['', validatorsCompanies],
      range_date: ['', [Validators.required]],
      device_id: ['', validatorsDevices],
      frequency: ['', [Validators.required]],
    });
  }

  applyLocale() {
    this.localeService.use(this.locale);
  }

  changeSelectCompany(companyId)
  {
        this.devices = [];
        
        this.form.setValue({
            company_id : companyId ,
            range_date : '',
            device_id : '',
            frequency : ''
        });
        this.getDevice(companyId);
        
  }

  getDevice(companyId) 
  {
    this.service
      .queryGet(`companies-devices?company_id=${companyId}`)
      .subscribe(
        (response: any) => {
          let devices = response.data;

          devices.forEach((element) => {
            this.devices.push(element.device);
          });
        },
        (err: any) => {
          console.log(err);
        }
      );
  }

  changeSelectDevice(deviceId)
    {
        this.service.queryGet(`devices/${deviceId}`)
            .subscribe (
                (response:any) =>
                {
                    console.log();

                    if(response.success)
                    {
                        this.device = response.data;
                        this.showDetail = true;
                    }
                    
                },
                (err:any) =>
                {
                    console.log(err);
                    
                }
            )
    }

    report(event: Event)
    {      
        this.reportIca = [];
        this.reportTemperature = [];
        this.reportHumidity = [];
        this.reportPressure = [];
        this.reportElevation = [];
          
        event.preventDefault();
		if (this.form.invalid) {
			this.form.markAllAsTouched();
			return;
		}

        let values = Object.assign({}, this.form.value);

        let start = moment(values.range_date[0]).format("YYYY-MM-DD HH:mm");
        let end = moment(values.range_date[1]).format("YYYY-MM-DD HH:mm");
        

        this.service.queryGet(`get-date-range?start_date=${start}&end_date=${end}&device_id=${values.device_id}&frequency=${values.frequency}`)
        .subscribe (
            (response:any) =>
            {

                let result = response.data;
                
                result.forEach(element => {                        
                    this.reportIca.push({ date : element.date , report : element.ica});
                    this.reportTemperature.push({ date : element.date , report : element.temperature});
                    this.reportHumidity.push({ date : element.date , report : element.humidity});
                    this.reportPressure.push({ date : element.date , report : element.pressure});
                    this.reportElevation.push({ date : element.date , report : element.elevation});
                    
                });

                this.initChartIca();
                this.initChartTemperature();
                this.initChartHumidity();
                this.initChartPressure();
                this.initChartElevation();         
            },
            (err:any) => {
                console.log(err);
                
            }
        )      
    }

    initChartIca()
    {   
        let label = []
        let ica = []
        this.reportIca.forEach(element => {
            label.push(element.date);
            ica.push(element.report);
        });

        this.showChartIca = true;

        //------------- Chart Months -----------//
        const dataIca = {
            labels: label,
            datasets: [
                {
                    name: "ICA", type: "bar",
                    values: ica
                }
            ]
        }

        setTimeout(() => {
            this.chartIca = new Chart("#chart-ica", {  // or a DOM element,
                                                         // new Chart() in case of ES6 module with above usage
                 title: "Reporte ICA",
                 data: dataIca,
                 type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                 height: 300,
                 colors: ['#C7B5FB']
             });
         }, 100);
    }

    initChartTemperature()
    {

        let label = []
        let temperature = []
        this.reportTemperature.forEach(element => {
            label.push(element.date);
            temperature.push(element.report);
        });

        this.showChartTemperature = true;

        //------------- Chart Months -----------//
        const dataTemperature = {
            labels: label,
            datasets: [
                {
                    name: "Humedad", type: "bar",
                    values: temperature
                }
            ]
        }

        setTimeout(() => {
            this.chartTemperature = new Chart("#chart-temperature", {  // or a DOM element,
                                                        // new Chart() in case of ES6 module with above usage
                title: "Reporte Temperatura",
                data: dataTemperature,
                type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                height: 300,
                colors: ['#C7B5FB']
            });
        }, 100);
    }

    initChartHumidity()
    {

        let label = []
        let humidity = []
        this.reportHumidity.forEach(element => {
            label.push(element.date);
            humidity.push(element.report);
        });

        this.showChartHumidity = true;

        //------------- Chart Months -----------//
        const dataHumidity = {
            labels: label,
            datasets: [
                {
                    name: "Humedad", type: "bar",
                    values: humidity
                }
            ]
        }

        setTimeout(() => {
            this.chartHumidity = new Chart("#chart-humidity", {  // or a DOM element,
                                                        // new Chart() in case of ES6 module with above usage
                title: "Reporte Humedad",
                data: dataHumidity,
                type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                height: 300,
                colors: ['#C7B5FB']
            });
        }, 100);
    }

    initChartPressure()
    {

        let label = []
        let pressure = []
        this.reportPressure.forEach(element => {
            label.push(element.date);
            pressure.push(element.report);
        });

        this.showChartPressure = true;

        //------------- Chart Months -----------//
        const dataPressure = {
            labels: label,
            datasets: [
                {
                    name: "Precisión", type: "bar",
                    values: pressure
                }
            ]
        }

        setTimeout(() => {
            this.chartPressure = new Chart("#chart-pressure", {  // or a DOM element,
                                                        // new Chart() in case of ES6 module with above usage
                title: "Reporte Presión",
                data: dataPressure,
                type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                height: 300,
                colors: ['#C7B5FB']
            });
        }, 100);
    }

    initChartElevation()
    {
        let label = []
        let elevation = []
        this.reportElevation.forEach(element => {
            label.push(element.date);
            elevation.push(element.report);
        });

        this.showChartElevation = true;

        //------------- Chart Months -----------//
        const dataElevation = {
            labels: label,
            datasets: [
                {
                    name: "Altura", type: "bar",
                    values: elevation
                }
            ]
        }

        setTimeout(() => {
            this.chartElevation = new Chart("#chart-elevation", {  // or a DOM element,
                                                        // new Chart() in case of ES6 module with above usage
                title: "Reporte Altura",
                data: dataElevation,
                type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                height: 300,
                colors: ['#C7B5FB']
            });
        }, 100);
    }

  showComment()
  {
    this.opened = true;

  }

  refresh(event)
  {      
    this.ngOnInit();
    this.opened = false;
  }
}
