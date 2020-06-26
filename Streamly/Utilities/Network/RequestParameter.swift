//
//  RequestParameter.swift
//  ContactList
//
//  Created by Ayush Parashar on 03/06/20.
//  Copyright © 2020 Ayush Parashar. All rights reserved.
//


import Alamofire

open class RequestParameter {
    
  var url: String
  
  /// Method type e.g. GET/POST etc.
  var method: Alamofire.HTTPMethod
  
  /// Headers needed for the api call
  var headers: [String: String]?
  
  /// Url encoded parameters
  var parameters: [String: Any]?

  fileprivate init(url: String, method: Alamofire.HTTPMethod, headers: [String: String]? = nil, parameters: [String: Any]? = nil) {
    self.url = url
    self.method = method
    self.headers = headers
    self.parameters = parameters
  }

  public static func createRequestParameter(_ url: String, method: Alamofire.HTTPMethod, headers: [String: String]? = nil, parameters: [String: String]? = nil) -> RequestParameter {
    debugPrint("url is : \(url)")
    return RequestParameter(url: url, method: method, headers: headers, parameters: parameters)
  }

}
