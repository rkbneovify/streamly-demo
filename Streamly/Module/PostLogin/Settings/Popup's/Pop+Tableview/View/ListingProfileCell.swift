//
//  ListingProfileCell.swift
//  Streamly
//
//  Created by Rohit Singh on 6/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ListingProfileCell: UITableViewCell {

    // MARK: - Properties
    static let reuseIdentifier = "ListingProfileCell"
 

    // MARK: - Outlets
    @IBOutlet weak var headerLbl      : UILabel!
    @IBOutlet weak var dataLbl        : UILabel!

    override func awakeFromNib() {
        super.awakeFromNib()
        self.selectionStyle = .none
        // Initialization code
    }

//    override func setSelected(_ selected: Bool, animated: Bool) {
//        super.setSelected(selected, animated: animated)
//
//        // Configure the view for the selected state
//    }

    // MARK: - Helpers
    func configureCell(_ header:String, _ data:String,flag:Bool,item:Int)
    {


        if !flag
        {
        self.headerLbl.text = "\(item + 1). \(header)"
         self.headerLbl.textAlignment = .left
        }

         self.dataLbl.text = data
        self.headerLbl.text = header
        self.dataLbl.font =  !flag ?  UIFont.init(name: "HelveticaNeue-Medium", size: 22.0) : UIFont.init(name: "HelveticaNeue-Light", size: 28.0)

    }

}
