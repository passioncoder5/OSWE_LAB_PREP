package com.example;

import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class VulnerableServlet extends HttpServlet {

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response) 
            throws ServletException, IOException {
        
        System.out.println("\n[+] Request received at /api/v1/ingest");
        
        try {
            // Get the input stream from the POST body
            InputStream inputStream = request.getInputStream();
            
            // Create an ObjectInputStream
            ObjectInputStream objectInputStream = new ObjectInputStream(inputStream);
            
            // The Vulnerability (Sink)
            System.out.println("[*] Attempting to deserialize object...");
            Object object = objectInputStream.readObject();
            
            System.out.println("[+] Deserialization complete. Object type: " + object.getClass().getName());
            response.getWriter().println("Object processed successfully.");

        } catch (ClassNotFoundException e) {
            System.err.println("[-] Class not found: " + e.getMessage());
            response.setStatus(500);
            response.getWriter().println("Class not found: " + e.getMessage());
        } catch (Exception e) {
            // NOTE: A successful exploit often causes an exception (ClassCastException)
            System.err.println("[-] Exception during deserialization: " + e.getMessage());
            e.printStackTrace(); 
            response.setStatus(500);
            response.getWriter().println("Exception: " + e.getMessage());
        }
    }
    
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html");
        response.getWriter().println("<h1>Java Deserialization Lab</h1>");
        response.getWriter().println("<p>Send POST requests with Java serialized objects to /api/v1/ingest</p>");
        response.getWriter().println("<p>Use Content-Type: application/octet-stream</p>");
    }
}
