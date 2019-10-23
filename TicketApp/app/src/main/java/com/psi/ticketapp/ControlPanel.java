package com.psi.ticketapp;

import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.ticketapp.R;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;


public class ControlPanel extends AppCompatActivity {

    Context context;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseUser muser = mAuth.getCurrentUser();

    @Override
    public void onCreate(Bundle savedInstanceState ) {
        super.onCreate(savedInstanceState);
        context = this;
        setContentView(R.layout.activity_control_panel);
        ((TextView)findViewById(R.id.welcome)).setText(muser.getDisplayName());

        Button createuser = findViewById(R.id.createuser);
        createuser.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                updateUser();
            }
        });
    }

    public void updateUser() {
        Toast.makeText(context, "user created!", Toast.LENGTH_SHORT).show();
    }
}
