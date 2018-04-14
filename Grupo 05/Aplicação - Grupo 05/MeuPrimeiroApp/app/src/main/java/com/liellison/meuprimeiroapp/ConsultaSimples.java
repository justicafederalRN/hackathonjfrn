package com.liellison.meuprimeiroapp;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class ConsultaSimples extends AppCompatActivity implements View.OnClickListener {
    Button buscaBT;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_consulta_simples);

        buscaBT =(Button) findViewById(R.id.buscaBT);

        buscaBT.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {
        Intent intent = new Intent(this, ResultadoBusca68.class);
        startActivity(intent);

    }
}
