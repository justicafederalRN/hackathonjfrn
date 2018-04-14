package com.liellison.meuprimeiroapp;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class ResultadoBusca68 extends AppCompatActivity {
    Button meiaoitobt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_resultado_busca68);

        meiaoitobt = (Button) findViewById(R.id.meiaoitobt);
        meiaoitobt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(ResultadoBusca68.this, Resultado68.class);
                startActivity(intent);
            }
        });
    }
}
