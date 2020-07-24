//
//  Request.swift
//  Streamly
//
//  Created by Rohit Singh on 5/18/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import Alamofire

class LoginRequest: NSObject {

    public var email: String?
    public var password: String?
    public var deviceID:String?
    
    func userLogin(complition: @escaping (LoginResponse?, String?) -> Swift.Void) {
        let parameter:[String:String] = ["email": self.email ?? "", "password": self.password ?? "","deviceid":deviceID ?? ""]
        let headers:HTTPHeaders = ["Content-Type": "application/x-www-form-urlencoded"]
        AF.request("https://streamlytv.com/api/auth/login", method: .post, parameters: parameter, encoding: URLEncoding.default, headers: headers).responseJSON{ (response) in
            debugPrint(response)
            guard let data = response.data else {return}
            do {
                let decoder = JSONDecoder()
                let loginData = try decoder.decode(LoginResponse.self, from: data)
                complition(loginData, nil)
            } catch let error {
                debugPrint("Err", error.localizedDescription)
                complition(nil, ValidationErrorMessages.somethingWentWrong.descriptipon())
            }
        }

    }

}
