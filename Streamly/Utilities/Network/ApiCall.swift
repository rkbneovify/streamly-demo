//
//  ApiCall.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//


import Alamofire

open class ApiCall {
    

  @discardableResult
  public static func getRegistredDeviceData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
    let url: String! = "http://100.26.190.92/api/auth/connecteddevice"
    let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
    let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

    let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
    return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
  }
}

