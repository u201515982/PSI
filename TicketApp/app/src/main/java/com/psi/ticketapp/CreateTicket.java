package com.psi.ticketapp;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.view.ContextThemeWrapper;
import android.view.HapticFeedbackConstants;
import android.view.MotionEvent;
import android.view.View;
import android.view.inputmethod.EditorInfo;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.constraintlayout.widget.ConstraintLayout;

import com.example.ticketapp.R;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.Timestamp;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class CreateTicket extends Activity {
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    Context context;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseUser FBuser = mAuth.getCurrentUser();
    boolean available;
    private int floorButton = -1;
    List<Button> FButtons;
    private int typeButton = -1;
    List<Button> TButtons;
    SharedPreferences sharedPreferences;
    TextView btn_desc_txt;
    public void hideKeyboard(){
        ((EditText) findViewById(R.id.ct_user)).onEditorAction(EditorInfo.IME_ACTION_DONE);
    }

    private boolean isEmpty(EditText etText) {
        return etText.getText().toString().trim().length() == 0;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        context = this;
        sharedPreferences = getPreferences(MODE_PRIVATE);
        db.collection("users").document(FBuser.getEmail().substring(0,FBuser.getEmail().indexOf("@"))).get().addOnCompleteListener(new OnCompleteListener<DocumentSnapshot>() {
            @Override
            public void onComplete(@NonNull Task<DocumentSnapshot> task) {
                if(task.isSuccessful()){
                    available = (boolean)task.getResult().get("available");
                }
            }
        });
        setContentView(R.layout.activity_create_ticket);

        FButtons = new ArrayList<>();
        TButtons = new ArrayList<>();
        configFloorButtons();
        configTypeButtons();

        final Button btn_desc_add = findViewById(R.id.ct_description_add);
        final ConstraintLayout layout_desc = findViewById(R.id.ct_description_layout_alt);
        btn_desc_txt = findViewById(R.id.ct_description_txt_alt);
        ImageButton btn_desc_del = findViewById(R.id.ct_description_del_alt);



        btn_desc_add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openWriter();
            }
        });

        btn_desc_txt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openWriter();
            }
        });

        btn_desc_del.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder builder = new AlertDialog.Builder(new ContextThemeWrapper(context, android.R.style.Theme_Material_Dialog_Alert));
                builder.setMessage("¿Borrar descripción?");
                builder.setPositiveButton("Si", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        dialog.dismiss();
                        layout_desc.setVisibility(View.GONE);
                        btn_desc_add.setVisibility(View.VISIBLE);
                        btn_desc_txt.setText(null);
                    }
                });
                builder.setNegativeButton("No", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        dialog.dismiss();
                    }
                });
                AlertDialog alert = builder.create();
                alert.show();
                TextView textView = alert.findViewById(android.R.id.message);
                textView.setTextSize(18);
            }
        });

        Button btn_create_ticket = findViewById(R.id.btn_create_ticket);
        Button btn_create_claim_ticket = findViewById(R.id.btn_create_claim_ticket);

        findViewById(R.id.layout_create_ticket).setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                hideKeyboard();
                return true;
            }
        });

        btn_create_ticket.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ticketCreator(false);
            }
        });

        btn_create_claim_ticket.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(available){
                    ticketCreator(true);
                }
                else Toast.makeText(context, "Usted ya se encuentra atendiendo un incidente!", Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void openWriter(){
        Intent i = new Intent(context, Writer.class);
        Bundle extras = new Bundle();
        extras.putInt("w",0);
        extras.putString("title","Descripción");
        extras.putString("text",btn_desc_txt.getText().toString());
        i.putExtras(extras);
        startActivityForResult(i,1);
        overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if(requestCode == 1 && resultCode == 1){
            btn_desc_txt.setText(data.getStringExtra("large_text"));
            findViewById(R.id.ct_description_layout_alt).setVisibility(View.VISIBLE);
            findViewById(R.id.ct_description_add).setVisibility(View.GONE);
        }
    }

    public void ticketCreator(boolean cc){
        hideKeyboard();
        View view = findViewById(R.id.btn_create_ticket);
        view.performHapticFeedback(HapticFeedbackConstants.VIRTUAL_KEY);

        long datecode = System.currentTimeMillis();
        EditText ct_user = findViewById(R.id.ct_user);
        String ticketid = "tk."+datecode;
        Map<String, Object> ticket = new HashMap<>();

        if(isEmpty(ct_user)){
            Toast.makeText(getApplicationContext(), "Nombre de usuario vacío!", Toast.LENGTH_LONG).show();
            ct_user.setText("");
        }else if(floorButton == -1){
            Toast.makeText(getApplicationContext(), "Indicar número de piso!", Toast.LENGTH_LONG).show();
        }else if(typeButton == -1){
            Toast.makeText(getApplicationContext(), "Indicar tipo de ticket!", Toast.LENGTH_LONG).show();
        }else{
            Date time_begin = new Date();
            String created_by = FBuser.getEmail().substring(0,FBuser.getEmail().indexOf("@"));
            String user = ct_user.getText().toString();
            String floor = Integer.toString(floorButton+1);
            String area = ((Spinner)findViewById(R.id.spin_area)).getSelectedItem().toString();
            String type = getType(typeButton);
            String description = null;
            if(btn_desc_txt.getText().toString().trim().length() != 0){
                description = btn_desc_txt.getText().toString();
            }
            String state = "pending";
            Date time_claimed = null;
            String claimed_by = null;
            if(cc){
                //db.collection("users").document(created_by).update("available", false);
                time_claimed = new Date();
                claimed_by = created_by;
                state = "claimed";
            }
            Timestamp time_end = null;
            String post_mortem = null;

            ticket.put("time_begin", time_begin);
            ticket.put("created_by", created_by);
            ticket.put("user", user);
            ticket.put("floor", floor);
            ticket.put("type", type);
            ticket.put("description", description);
            ticket.put("state", state);
            ticket.put("time_claimed", time_claimed);
            ticket.put("claimed_by", claimed_by);
            ticket.put("time_end", time_end);
            ticket.put("post_mortem", post_mortem);
            ticket.put("area", area);

            db.collection("root").document("tk.0").update("last", datecode);

            db.collection("ticket").document(ticketid).set(ticket)
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void aVoid) {
                            Toast.makeText(getApplicationContext(), "ticket creado", Toast.LENGTH_LONG).show();
                        }
                    });
            ct_user.onEditorAction(EditorInfo.IME_ACTION_DONE);
            finish();
            overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
    }

    public void configFloorButtons(){
        final Button btn1 = findViewById(R.id.F1);
        final Button btn2 = findViewById(R.id.F2);
        final Button btn3 = findViewById(R.id.F3);
        final Button btn4 = findViewById(R.id.F4);
        final Button btn5 = findViewById(R.id.F5);
        final Button btn6 = findViewById(R.id.F6);
        final Button btn7 = findViewById(R.id.F7);
        final Button btn8 = findViewById(R.id.F8);
        final Button btn9 = findViewById(R.id.F9);
        final Button btn10 = findViewById(R.id.F10);
        FButtons.add(btn1);
        FButtons.add(btn2);
        FButtons.add(btn3);
        FButtons.add(btn4);
        FButtons.add(btn5);
        FButtons.add(btn6);
        FButtons.add(btn7);
        FButtons.add(btn8);
        FButtons.add(btn9);
        FButtons.add(btn10);
        final Spinner spinner = findViewById(R.id.spin_area);
        final ArrayAdapter<CharSequence> adapter1 = ArrayAdapter.createFromResource(this, R.array.piso1, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter25 = ArrayAdapter.createFromResource(this, R.array.piso25, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter6 = ArrayAdapter.createFromResource(this, R.array.piso6, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter7 = ArrayAdapter.createFromResource(this, R.array.piso7, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter8 = ArrayAdapter.createFromResource(this, R.array.piso8, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter9 = ArrayAdapter.createFromResource(this, R.array.piso9, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapter10 = ArrayAdapter.createFromResource(this, R.array.piso10, android.R.layout.simple_spinner_item);
        final ArrayAdapter<CharSequence> adapterDEF = ArrayAdapter.createFromResource(this, R.array.areaspace, android.R.layout.simple_spinner_item);
        adapterDEF.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(adapterDEF);
        btn1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(0);
                hideKeyboard();
                adapter1.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter1);
            }
        });
        btn2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(1);
                hideKeyboard();
                adapter25.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter25);
            }
        });
        btn3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(2);
                hideKeyboard();
                adapter25.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter25);
            }
        });
        btn4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(3);
                hideKeyboard();
                adapter25.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter25);
            }
        });
        btn5.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(4);
                hideKeyboard();
                adapter25.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter25);
            }
        });
        btn6.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(5);
                hideKeyboard();
                adapter6.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter6);
            }
        });
        btn7.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(6);
                hideKeyboard();
                adapter7.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter7);
            }
        });
        btn8.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(7);
                hideKeyboard();
                adapter8.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter8);
            }
        });
        btn9.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(8);
                hideKeyboard();
                adapter9.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter9);
            }
        });
        btn10.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectFloorButtons(9);
                hideKeyboard();
                adapter10.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinner.setAdapter(adapter10);
            }
        });
    }

    public void selectFloorButtons(int eb) {
        floorButton = eb;
        for(int i=0; i<10;i++){
            if (floorButton!= i){
                FButtons.get(i).setSelected(false);
                FButtons.get(i).setBackground(getDrawable(R.drawable.shape_green_outline));
                FButtons.get(i).setTextColor(Color.parseColor("#5ED540"));
            }else{
                FButtons.get(i).setSelected(true);
                FButtons.get(i).setBackground(getDrawable(R.drawable.shape_green_fill));
                FButtons.get(i).setTextColor(Color.parseColor("#FFFFFF"));
            }
        }
    }

    public void configTypeButtons(){
        final Button btnSupport = findViewById(R.id.ctt_support);
        final Button btnPrinter = findViewById(R.id.ctt_printer);
        final Button btnSisged = findViewById(R.id.ctt_sisged);
        final Button btnSiaf = findViewById(R.id.ctt_siaf);
        final Button btnSaps = findViewById(R.id.ctt_saps);
        final Button btnWeb = findViewById(R.id.ctt_web);
        final Button btnPte = findViewById(R.id.ctt_pte);
        TButtons.add(btnSupport);
        TButtons.add(btnPrinter);
        TButtons.add(btnSisged);
        TButtons.add(btnSiaf);
        TButtons.add(btnSaps);
        TButtons.add(btnWeb);
        TButtons.add(btnPte);
        btnSupport.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(0);
                hideKeyboard();
            }
        });
        btnPrinter.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(1);
                hideKeyboard();
            }
        });
        btnSisged.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(2);
                hideKeyboard();
            }
        });
        btnSiaf.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(3);
                hideKeyboard();
            }
        });
        btnSaps.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(4);
                hideKeyboard();
            }
        });
        btnWeb.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(5);
                hideKeyboard();
            }
        });
        btnPte.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectTypeButtons(6);
                hideKeyboard();
            }
        });
    }

    public void selectTypeButtons(int tb) {
        typeButton = tb;
        for(int i=0; i<7;i++){
            if (typeButton!= i){
                TButtons.get(i).setSelected(false);
                TButtons.get(i).setBackground(getDrawable(R.drawable.shape_blue_outline));
                TButtons.get(i).setTextColor(Color.parseColor("#1CC2C2"));
            }else{
                TButtons.get(i).setSelected(true);
                TButtons.get(i).setBackground(getDrawable(R.drawable.shape_blue_fill));
                TButtons.get(i).setTextColor(Color.parseColor("#FFFFFF"));
            }
        }
    }

    public String getType(int t){
        String t_name = "";
        switch(t){
            case 0: t_name = "Soporte"; break;
            case 1: t_name = "Impresora"; break;
            case 2: t_name = "SISGED"; break;
            case 3: t_name = "SIAF"; break;
            case 4: t_name = "SAPS"; break;
            case 5: t_name = "WEB"; break;
            case 6: t_name = "PTE"; break;
        }
        return t_name;
    }
}
