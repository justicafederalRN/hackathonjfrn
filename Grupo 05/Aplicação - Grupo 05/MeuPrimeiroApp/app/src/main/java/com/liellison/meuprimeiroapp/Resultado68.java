package com.liellison.meuprimeiroapp;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class Resultado68 extends AppCompatActivity {
    Button iniciobt, defesabt, provasbt, sentencabt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_resultado68);

        iniciobt =(Button) findViewById(R.id.iniciobt);
        defesabt = (Button) findViewById(R.id.defesabt);
        provasbt = (Button) findViewById(R.id.provasbt);
        sentencabt = (Button) findViewById(R.id.sentencabt);

        iniciobt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Resultado68.this, Inicial68.class);
                startActivity(intent);
            }
        });
        defesabt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Resultado68.this, Defesa68.class);
                startActivity(intent);
            }
        });
        provasbt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Resultado68.this, Prova.class);
                startActivity(intent);
            }
        });
        sentencabt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Resultado68.this, Sentenca68.class);
                startActivity(intent);
            }
        });
}
}
