package com.liellison.meuprimeiroapp;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class MainActivity extends AppCompatActivity implements View.OnClickListener {
    Button consultaSimpleBT, loginBT, registroBT;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        consultaSimpleBT = (Button) findViewById(R.id.consultaSimpleBT);
        loginBT = (Button) findViewById(R.id.loginBT);
       // registroBT = (Button)findViewById(R.id.registroBT);

        consultaSimpleBT.setOnClickListener(this);
        loginBT.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainActivity.this, Login.class);
                startActivity(intent);
            }
        });
       /* registroBT.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainActivity.this, Registro.class);
                startActivity(intent);
            }
        }); */
    }

    @Override
    public void onClick(View view) {
        Intent intent = new Intent(this,ConsultaSimples.class);
        startActivity(intent);
    }
}
