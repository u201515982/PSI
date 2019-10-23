package com.psi.ticketapp;

import android.app.AlertDialog;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Build;
import android.os.Bundle;
import android.text.method.PasswordTransformationMethod;
import android.util.Log;
import android.view.ContextThemeWrapper;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.view.inputmethod.EditorInfo;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.psi.ticketapp.Model.Ticket;
import com.example.ticketapp.R;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.Timestamp;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.auth.UserProfileChangeRequest;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.InstanceIdResult;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ScrollingActivity extends AppCompatActivity {
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    Context context;
    List<Ticket> tickets = new ArrayList<>();
    TicketAdapter adapter;
    int FILTER_STATE;
    List<Button> filterBar = new ArrayList<>();
    String CURRENT_TK;
    SharedPreferences sharedPreferences;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseUser user = mAuth.getCurrentUser();
    RecyclerView recycler;

    public void hideKeyboardSetup(){
        ((EditText) findViewById(R.id.myusername)).onEditorAction(EditorInfo.IME_ACTION_DONE);
        ((EditText) findViewById(R.id.mypassword)).onEditorAction(EditorInfo.IME_ACTION_DONE);
        ((EditText) findViewById(R.id.mypassword_confirm)).onEditorAction(EditorInfo.IME_ACTION_DONE);
    }
    public void hideKeyboardLogin(){
        ((EditText) findViewById(R.id.login_user)).onEditorAction(EditorInfo.IME_ACTION_DONE);
        ((EditText) findViewById(R.id.login_password)).onEditorAction(EditorInfo.IME_ACTION_DONE);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        context = this;
        sharedPreferences = getPreferences(MODE_PRIVATE);
        FILTER_STATE = sharedPreferences.getInt("FILTER_STATE", 0);
        setContentView(R.layout.activity_scrolling);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            String channelId  = "default_id";
            String channelName = "default_name";
            NotificationManager notificationManager =
                    getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(new NotificationChannel(channelId,
                    channelName, NotificationManager.IMPORTANCE_LOW));
        }
        /*if (getIntent().getExtras() != null) {
            for (String key : getIntent().getExtras().keySet()) {
                Object value = getIntent().getExtras().get(key);
            }
        }*/

        /*db.collection("test")
                .addSnapshotListener(new EventListener<QuerySnapshot>() {
                    @Override
                    public void onEvent(@Nullable QuerySnapshot value,
                                        @Nullable FirebaseFirestoreException e) {
                        if (e != null) {
                            Log.w("TAAG", "Listen failed.", e);
                            return;
                        }

                        List<String> cities = new ArrayList<>();
                        for (QueryDocumentSnapshot doc : value) {
                            if (doc.get("name") != null) {
                                cities.add(doc.get("name").toString());
                            }
                        }
                        Log.d("TaAG", "Current cites in CA: " + cities);
                        Toast.makeText(context, "Current cites in CA: " + cities, Toast.LENGTH_SHORT).show();
                    }
                });*/

        updateUI(user);
    }

    public void updateUI(FirebaseUser user){
        if (user == null) {
            setContentView(R.layout.activity_login);
            final Button btnLogin = findViewById(R.id.btn_login);
            final EditText userName = findViewById(R.id.login_user);
            final EditText password = findViewById(R.id.login_password);
            final ImageButton psw_show_hide = findViewById(R.id.psw_show_hide);
            psw_show_hide.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(psw_show_hide.getTag().toString().equals("show")){
                        psw_show_hide.setTag("hide");
                        password.setTransformationMethod(null);
                        psw_show_hide.setImageDrawable(getResources().getDrawable(R.drawable.baseline_visibility_24));
                    } else{
                        psw_show_hide.setTag("show");
                        password.setTransformationMethod(new PasswordTransformationMethod());
                        psw_show_hide.setImageDrawable(getResources().getDrawable(R.drawable.baseline_visibility_off_24));
                    }
                }
            });
            btnLogin.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(userName.getText().toString().trim().equals("") || password.getText().toString().trim().equals("")){
                        Toast.makeText(context, "Ingresar Datos!", Toast.LENGTH_SHORT).show();
                        userName.setText(userName.getText().toString().trim());
                    }else{
                        signIn(userName.getText().toString().trim(), password.getText().toString());
                    }
                }
            });
            findViewById(R.id.login_layout).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    hideKeyboardLogin();
                }
            });
        }else if(user.getDisplayName() == null){
            mAuth.signOut();
            recreate();
        } else {
            //Toast.makeText(context, "Welcome ".concat(user.getDisplayName()), Toast.LENGTH_SHORT).show();
            setContentView(R.layout.activity_scrolling);
            Toolbar toolbar = findViewById(R.id.toolbar);
            setSupportActionBar(toolbar);
            FloatingActionButton fab = findViewById(R.id.fab);
            fab.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent intent = new Intent(context, CreateTicket.class);
                    startActivity(intent);
                    overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
                }
            });
            setFilterButtons();
            recycler = findViewById(R.id.my_recycler_view);
            recycler.setLayoutManager(new LinearLayoutManager(context));
            adapter = new TicketAdapter(context, tickets);
            updateTicketList();
        }
    }

    public void setupUser(){
        setContentView(R.layout.activity_user_setup);
        final EditText myusername = findViewById(R.id.myusername);
        final EditText mypassword = findViewById(R.id.mypassword);
        final EditText mypassword_confirm = findViewById(R.id.mypassword_confirm);
        final TextView psw_match = findViewById(R.id.psw_match);
        final ImageButton psw_show_hide_SU = findViewById(R.id.psw_show_hide_SU);
        final Button first_ok = findViewById(R.id.first_ok);
        first_ok.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                first_ok.setEnabled(false);
                if(myusername.getText().toString().trim().isEmpty()){
                    Toast.makeText(context, "Ingrese su nombre", Toast.LENGTH_SHORT).show();
                }else if(mypassword.getText().toString().trim().isEmpty() || mypassword_confirm.getText().toString().trim().isEmpty()){
                    Toast.makeText(context, "Ingrese una contraseña", Toast.LENGTH_SHORT).show();
                }else if(mypassword.getText().toString().trim().length() < 6 || mypassword_confirm.getText().toString().trim().length() < 6){
                    Toast.makeText(context, "La contraseña debe tener 6 o más caracteres", Toast.LENGTH_SHORT).show();
                }else if(mypassword.getText().toString().trim().equals(mypassword_confirm.getText().toString().trim())){
                    final FirebaseUser auxusr = FirebaseAuth.getInstance().getCurrentUser();
                    UserProfileChangeRequest profileUpdates = new UserProfileChangeRequest.Builder().setDisplayName(myusername.getText().toString()).build();
                    auxusr.updateProfile(profileUpdates);
                    auxusr.updatePassword(mypassword.getText().toString());
                    Map<String,Object> myUserData = new HashMap<>();
                    final String newusername = auxusr.getEmail().substring(0,auxusr.getEmail().indexOf("@"));
                    myUserData.put("available", true);
                    myUserData.put("rol", "support");
                    myUserData.put("token", "");
                    db.collection("users").document(newusername).set(myUserData)
                            .addOnCompleteListener(new OnCompleteListener<Void>() {
                                @Override
                                public void onComplete(@NonNull Task<Void> task) {
                                    if (task.isSuccessful()) {
                                        FirebaseInstanceId.getInstance().getInstanceId()
                                                .addOnCompleteListener(new OnCompleteListener<InstanceIdResult>() {
                                                    @Override
                                                    public void onComplete(@NonNull Task<InstanceIdResult> task) {
                                                        if (!task.isSuccessful()) {
                                                            return;
                                                        }else{
                                                            String token = task.getResult().getToken();
                                                            db.collection("users").document(newusername).update("token", token).addOnCompleteListener(new OnCompleteListener<Void>() {
                                                                @Override
                                                                public void onComplete(@NonNull Task<Void> task) {
                                                                    if(task.isSuccessful()){
                                                                        recreate();
                                                                    }
                                                                }
                                                            });

                                                        }
                                                    }
                                                });
                                    }
                                }
                            });
                }else{
                    psw_match.setText("Las contraseñas no coinciden.");
                    mypassword.setBackground(getDrawable(R.drawable.shape_red_sqr));
                    mypassword_confirm.setBackground(getDrawable(R.drawable.shape_red_sqr));
                }
                first_ok.setEnabled(true);
            }
        });
        findViewById(R.id.layout_setup_user).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                hideKeyboardSetup();
            }
        });
        mypassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                psw_match.setText(" ");
                mypassword.setBackground(getDrawable(R.drawable.shape_black_sqr));
                mypassword_confirm.setBackground(getDrawable(R.drawable.shape_black_sqr));
            }
        });
        mypassword_confirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                psw_match.setText(" ");
                mypassword.setBackground(getDrawable(R.drawable.shape_black_sqr));
                mypassword_confirm.setBackground(getDrawable(R.drawable.shape_black_sqr));
            }
        });
        psw_show_hide_SU.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(psw_show_hide_SU.getTag().toString().equals("show")){
                    psw_show_hide_SU.setTag("hide");
                    mypassword.setTransformationMethod(null);
                    mypassword_confirm.setTransformationMethod(null);
                    psw_show_hide_SU.setImageDrawable(getResources().getDrawable(R.drawable.baseline_visibility_24));
                } else{
                    psw_show_hide_SU.setTag("show");
                    mypassword.setTransformationMethod(new PasswordTransformationMethod());
                    mypassword_confirm.setTransformationMethod(new PasswordTransformationMethod());
                    psw_show_hide_SU.setImageDrawable(getResources().getDrawable(R.drawable.baseline_visibility_off_24));
                }
            }
        });

    }

    public void signIn(String username, String password){
        String userEmail = username.concat("@psi.gob.pe");
        mAuth.signInWithEmailAndPassword(userEmail, password)
                .addOnCompleteListener(this, new OnCompleteListener<AuthResult>() {
                    @Override
                    public void onComplete(@NonNull Task<AuthResult> task) {
                        if (task.isSuccessful()){
                            FirebaseInstanceId.getInstance().getInstanceId()
                                    .addOnCompleteListener(new OnCompleteListener<InstanceIdResult>() {
                                        @Override
                                        public void onComplete(@NonNull Task<InstanceIdResult> task) {
                                            if (!task.isSuccessful()) {
                                                return;
                                            }
                                            if(FirebaseAuth.getInstance().getCurrentUser().getDisplayName() == null){
                                                setupUser();
                                            }else{
                                                String token = task.getResult().getToken();
                                                db.collection("users").document(FirebaseAuth.getInstance().getCurrentUser(                     ).getDisplayName())
                                                        .update("token", token);
                                                recreate();
                                            }

                                        }
                                    });

                        }
                        else{
                            Toast.makeText(context, "El usuario y la contraseña no coinciden.", Toast.LENGTH_SHORT).show();
                        }
                    }
                });
    }

    public void updateTicketList(){
        filterButtonAble(false);
        recycler.setAdapter(adapter);
        tickets.clear();
        db.collection("ticket").get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                String validate = document.get("state").toString();
                                switch(FILTER_STATE){
                                    case 0: {
                                        if(validate.equals("pending") || validate.equals("claimed")){
                                            Ticket auxT = getTicketData(document.getId(), document.getData());
                                            tickets.add(auxT);
                                        }
                                        break;
                                    }
                                    case 1: {
                                        if(validate.equals("pending")){
                                            Ticket auxT = getTicketData(document.getId(), document.getData());
                                            tickets.add(auxT);
                                        }
                                        break;
                                    }
                                    case 2: {
                                        if(validate.equals("claimed")){
                                            Ticket auxT = getTicketData(document.getId(), document.getData());
                                            tickets.add(auxT);
                                        }
                                        break;
                                    }
                                    case 3: {
                                        if(validate.equals("claimed") || validate.equals("pending")){
                                            Ticket auxT = getTicketData(document.getId(), document.getData());
                                            tickets.add(auxT);
                                        }
                                        break;
                                    }
                                }
                                //adapter.notifyDataSetChanged();
                            }
                            adapter.notifyDataSetChanged();
                        }
                        filterButtonAble(true);
                    }
                });
        filterButtonColor(FILTER_STATE);
    }

    public void setFilterButtons(){
        Button b1 = findViewById(R.id.b1);
        Button b2 = findViewById(R.id.b2);
        Button b3 = findViewById(R.id.b3);
        Button b4 = findViewById(R.id.b4);
        filterBar.add(b1);
        filterBar.add(b2);
        filterBar.add(b3);
        filterBar.add(b4);
        b1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                filterButtonAction(0);
            }
        });
        b2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                filterButtonAction(1);
            }
        });
        b3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                filterButtonAction(2);
            }
        });
        b4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                filterButtonAction(3);
            }
        });
    }
    public void filterButtonAction(int n){
        if(FILTER_STATE != n){
            FILTER_STATE = n;
            sharedPreferences.edit().putInt("FILTER_STATE", FILTER_STATE).apply();
            filterButtonColor(n);
        }
        updateTicketList();
    }
    public void filterButtonColor(int n){
        for(int i = 0; i < 4; i++){
            if(i!=n){
                filterBar.get(i).setTypeface(Typeface.DEFAULT);
                filterBar.get(i).setTextColor(Color.parseColor("#7E7E7E"));
            }else{
                filterBar.get(i).setTypeface(Typeface.DEFAULT_BOLD);
                filterBar.get(n).setTextColor(Color.parseColor("#FFFFFF"));
            }
        }
    }
    public void filterButtonAble(boolean act){
        if(act){
            for(int i = 0; i < 4; i++)
                filterBar.get(i).setEnabled(true);
        }else{
            for(int i = 0; i < 4; i++)
                filterBar.get(i).setEnabled(false);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_scrolling, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        /*if (id == R.id.action_menu) {
            Intent intent = new Intent(context, ControlPanel.class);
            startActivity(intent);
            overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
            return true;
        }else */if(id == R.id.action_logout){
            AlertDialog.Builder builder = new AlertDialog.Builder(new ContextThemeWrapper(context, android.R.style.Theme_Material_Dialog_Alert));
            builder.setMessage("¿Desea cerrar sesión?");
            builder.setPositiveButton("Si", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    dialog.dismiss();
                    mAuth.signOut();
                    recreate();
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
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onResume() {
        super.onResume();
        CURRENT_TK = sharedPreferences.getString("CURRENT_TK", "");
        db.collection("root").document("tk.0").get().addOnCompleteListener(new OnCompleteListener<DocumentSnapshot>() {
            @Override
            public void onComplete(@NonNull Task<DocumentSnapshot> task) {
                if(task.isSuccessful()){
                    DocumentSnapshot doc = task.getResult();
                    String DB_CURRENT_TK = doc.get("last").toString();
                    /*if(!CURRENT_TK.equals(DB_CURRENT_TK)){
                        sharedPreferences.edit().putString("CURRENT_TK", DB_CURRENT_TK).apply();*/
                        if(user != null) updateTicketList();
                    /*}*/
                }
            }
        });
    }

    public Ticket getTicketData(String tkid, Map<String, Object> aTicket){
        //String ticketid = tkid;
        Timestamp time_begin = (Timestamp)aTicket.get("time_begin");
        String created_by = aTicket.get("created_by").toString();
        String user = aTicket.get("user").toString();
        String floor = aTicket.get("floor").toString();
        String type = aTicket.get("type").toString();
        String state = aTicket.get("state").toString();
        String area = aTicket.get("area").toString();
        String description = null;
        Timestamp time_claimed = null;
        String claimed_by = null;
        Timestamp time_end = null;
        String post_mortem = null;
        if(aTicket.get("description") != null){
            description = aTicket.get("description").toString();
        }
        if(aTicket.get("time_claimed") != null){
            time_claimed = (Timestamp)aTicket.get("time_claimed");
            claimed_by = aTicket.get("claimed_by").toString();
        }
        if(aTicket.get("time_end") != null){
            time_end = (Timestamp) aTicket.get("time_end");
        }
        if(aTicket.get("post_mortem") != null){
            post_mortem = aTicket.get("post_mortem").toString();
        }

        Ticket ticketObj = new Ticket(tkid, time_begin, created_by, user, floor, type, description, state, time_claimed, claimed_by, time_end, post_mortem, area);

        return ticketObj;
    }
}
