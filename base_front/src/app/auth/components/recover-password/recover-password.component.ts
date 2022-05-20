import { Component, OnInit } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-recover-password',
  templateUrl: './recover-password.component.html',
  styleUrls: ['./recover-password.component.scss']
})
export class RecoverPasswordComponent implements OnInit {

  	form: FormGroup;

  	constructor(private formBuilder: FormBuilder,
                private service: ApirestService,
                public singleton: SingletonService,
                private router: Router) 
  	{
  		this.buildForm(); 
  	}

  	ngOnInit(): void 
  	{
  	}

    /**
     *
     * It creates the login form with validations
     *
     */
    
  	private buildForm() 
  	{
	    this.form = this.formBuilder.group({
	      	email: ['', [Validators.required, Validators.email]],
	      /*	confirm_email: ['', [Validators.required, Validators.email]],*/
	    });
    }

    /**
     *
     * Recover password function
     *
     */
    
    recoverPassword(event: Event)
    {
        event.preventDefault();
        if (this.form.valid) 
        {
            const body = this.form.value;
            
            this.service.queryPostRegular('recover-password', body).subscribe(
                (response: any) =>
                {   
                    if(response.success)
                    {
                    	this.singleton.showAlert({type: 'success', content: response.success});
                        this.router.navigate(['/auth/login']);
                    }
                },
                (err: any) => {}
            );
        }
    }
}
