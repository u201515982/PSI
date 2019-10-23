package com.psi.ticketapp;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;

import com.example.ticketapp.R;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.FirebaseFirestore;

public class Writer extends Activity {
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    Context context;

    public void hideKeyboard(){
        ((EditText)findViewById(R.id.large_text)).onEditorAction(EditorInfo.IME_ACTION_DONE);
    }

    @Override
    public void onCreate( Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        context = this;
        setContentView(R.layout.activity_write);

        Bundle extras = getIntent().getExtras();
        int w = extras.getInt("w");
        String mytitle = extras.getString("title");
        TextView title = findViewById(R.id.write_title);
        title.setText(mytitle);
        final String mytext = extras.getString("text");
        final EditText large_text = findViewById(R.id.large_text);
        large_text.requestFocus();
        ImageButton btn_cancel = findViewById(R.id.write_cancel);
        ImageButton btn_send = findViewById(R.id.write_send);
        btn_cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                hideKeyboard();
                finish();
                overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
            }
        });

        if(w == 0){
            large_text.setText(mytext);
            btn_send.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(large_text.getText().toString().trim().length() == 0){
                        Toast.makeText(context, "Campo de texto vacío", Toast.LENGTH_SHORT).show();
                        large_text.setText("");
                    }else{
                        hideKeyboard();
                        Intent intent = getIntent();
                        intent.putExtra("large_text", large_text.getText().toString().trim());
                        setResult(1, intent);
                        finish();
                        overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
                    }
                }
            });
        }else{
            btn_send.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(large_text.getText().toString().trim().length() == 0){
                        Toast.makeText(context, "Campo de texto vacío", Toast.LENGTH_SHORT).show();
                        large_text.setText("");
                    }else{
                        db.collection("ticket").document(mytext)
                                .update("post_mortem", large_text.getText().toString().trim()).addOnCompleteListener(new OnCompleteListener<Void>() {
                            @Override
                            public void onComplete(@NonNull Task<Void> task) {
                                if(task.isSuccessful()){
                                    hideKeyboard();
                                    finish();
                                    overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
                                }
                            }
                        });

                    }
                }
            });
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        hideKeyboard();
        overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
    }
}
