package com.psi.ticketapp.Model;

import com.google.firebase.Timestamp;

public class Ticket {
    private String ticketid;
    private Timestamp time_begin;
    private String created_by;
    private String user;
    private String floor;
    private String type;
    private String description;
    private String state;
    private Timestamp time_claimed;
    private String claimed_by;
    private Timestamp time_end;
    private String post_mortem;
    private String area;

    public Ticket(String ticketid, Timestamp time_begin, String created_by, String user, String floor, String type,
                  String description, String state, Timestamp time_claimed, String claimed_by, Timestamp time_end, String post_mortem, String area) {
        this.ticketid = ticketid;
        this.time_begin = time_begin;
        this.created_by = created_by;
        this.user = user;
        this.floor = floor;
        this.type = type;
        this.description = description;
        this.state = state;
        this.claimed_by = claimed_by;
        this.time_claimed = time_claimed;
        this.time_end = time_end;
        this.post_mortem = post_mortem;
        this.area = area;
    }

    public String getTicketid() {
        return ticketid;
    }

    public Timestamp getTime_begin() {
        return time_begin;
    }

    public String getUser() {
        return user;
    }

    public String getFloor() {
        return floor;
    }

    public String getType() {
        return type;
    }

    public String getState() {
        return state;
    }

    public String getClaimed_by() {
        return claimed_by;
    }

    public String getArea() { return area; }
}
